<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Каталог';

    protected static ?string $navigationLabel = 'Категории';

    protected static ?string $modelLabel = 'категорию';

    protected static ?string $pluralModelLabel = 'категории';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Название')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Set $set, Get $get) {
                                        if ($operation !== 'create' && $get('slug') !== Str::slug($get('name'))) {
                                            return;
                                        }

                                        $set('slug', Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label(__('filament.forms.fields.url.label'))
                                    ->required()
                                    ->maxLength(255)
                                    ->rules([
                                        function (Get $get) {
                                            return Rule::unique('categories', 'slug')
                                                ->where('parent_id', $get('parent_id'))
                                                ->ignore($get('id'));
                                        },
                                    ])
                                    ->helperText(__('filament.forms.fields.url.helper_text')),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Описание')
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'link',
                                        'orderedList',
                                        'unorderedList',
                                        'redo',
                                        'undo',
                                    ]),

                                Forms\Components\Select::make('parent_id')
                                    ->label('Родительская категория')
                                    ->relationship(
                                        'parent',
                                        'name',
                                        fn (Builder $query, ?Category $record) => $query->when(
                                            $record,
                                            fn (Builder $query) => $query->where('id', '!=', $record->id)
                                                ->whereNotIn('id', $record->getAllDescendants()->pluck('id')),
                                        ),
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->nullable()
                                    ->helperText('Выберите родительскую категорию, если это подкатегория'),
                            ]),

                        Forms\Components\Section::make('Медиа')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('icon')
                                    ->label('Иконка')
                                    ->collection('icon')
                                    ->image()
                                    ->downloadable()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Статус')
                            ->schema([
                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Видимость')
                                    ->helperText('Включить/выключить отображение категории на сайте')
                                    ->default(true),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Порядок сортировки')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Чем меньше число, тем выше категория в списке'),
                            ]),

                        Forms\Components\Section::make('Мета данные')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label(__('filament.forms.fields.meta_title.label'))
                                    ->helperText('Оставьте пустым, чтобы использовать название категории'),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label(__('filament.forms.fields.meta_description.label'))
                                    ->rows(3)
                                    ->helperText('Оставьте пустым, чтобы использовать описание категории'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->columns([
                SpatieMediaLibraryImageColumn::make('icon')
                    ->label('Иконка')
                    ->circular()
                    ->collection('icon'),

                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('full_path')
                    ->label('Путь')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('children_count')
                    ->label('Подкатегории')
                    ->counts('children')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_visible')
                    ->label('Видимость')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Видимость')
                    ->placeholder('Все')
                    ->trueLabel('Только видимые')
                    ->falseLabel('Только скрытые'),

                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Родительская категория')
                    ->relationship('parent', 'name')
                    ->placeholder('Все')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Category $record) {
                        // Перемещаем дочерние категории к родителю удаляемой категории
                        $record->children()->update(['parent_id' => $record->parent_id]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            // Перемещаем дочерние категории к родителям удаляемых категорий
                            foreach ($records as $record) {
                                $record->children()->update(['parent_id' => $record->parent_id]);
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChildrenRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
