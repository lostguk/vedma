<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomePageContentResource\Pages;
use App\Models\HomePageContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomePageContentResource extends Resource
{
    protected static ?string $model = HomePageContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?string $navigationLabel = 'Главная страница';

    protected static ?string $modelLabel = 'Главная страница';

    protected static ?string $pluralModelLabel = 'Главная страница';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Первый экран')
                            ->schema([
                                Forms\Components\Section::make('Текст')
                                    ->schema([
                                        Forms\Components\TextInput::make('hero_title')
                                            ->label('Заголовок')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('hero_subtitle')
                                            ->label('Подзаголовок')
                                            ->rows(2),
                                        Forms\Components\TextInput::make('hero_button_label')
                                            ->label('Текст кнопки')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('hero_button_url')
                                            ->label('Ссылка кнопки')
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),
                                Forms\Components\Section::make('Изображение')
                                    ->schema([
                                        Forms\Components\FileUpload::make('hero_image_path')
                                            ->label('Фоновое изображение')
                                            ->image()
                                            ->directory('home')
                                            ->imageEditor()
                                            ->previewable(),
                                    ]),
                                Forms\Components\Section::make('Три преимущества')
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                // Карточка 1
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('hero_feature_1_text')
                                                            ->label('Карточка 1 — текст')
                                                            ->maxLength(255),

                                                        //                                                        Forms\Components\FileUpload::make('hero_feature_1_image_path')
                                                        //                                                            ->label('Карточка 1 — иконка')
                                                        //                                                            ->directory('home')
                                                        //                                                            ->image()
                                                        //                                                            ->previewable(),
                                                    ]),

                                                // Карточка 2
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('hero_feature_2_text')
                                                            ->label('Карточка 2 — текст')
                                                            ->maxLength(255),

                                                        //                                                        Forms\Components\FileUpload::make('hero_feature_2_image_path')
                                                        //                                                            ->label('Карточка 2 — иконка')
                                                        //                                                            ->directory('home')
                                                        //                                                            ->image()
                                                        //                                                            ->previewable(),
                                                    ]),

                                                // Карточка 3
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('hero_feature_3_text')
                                                            ->label('Карточка 3 — текст')
                                                            ->maxLength(255),

                                                        //                                                        Forms\Components\FileUpload::make('hero_feature_3_image_path')
                                                        //                                                            ->label('Карточка 3 — иконка')
                                                        //                                                            ->directory('home')
                                                        //                                                            ->image()
                                                        //                                                            ->previewable(),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Наша магия и цифры')
                            ->schema([
                                Forms\Components\Section::make('Верхняя часть')
                                    ->schema([
                                        Forms\Components\TextInput::make('about_title')
                                            ->label('Заголовок')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('about_description')
                                            ->label('Описание')
                                            ->rows(3),
                                    ]),

                                Forms\Components\Section::make('Почему нам доверяют')
                                    ->schema([
                                        Forms\Components\TextInput::make('about_trust_title')
                                            ->label('Заголовок блока')
                                            ->maxLength(255),

                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                // Карточка 1
                                                Forms\Components\Group::make([
                                                    Forms\Components\TextInput::make('about_trust_feature_1_title')
                                                        ->label('Карточка 1 — заголовок')
                                                        ->maxLength(255),

                                                    Forms\Components\FileUpload::make('about_trust_feature_1_image_path')
                                                        ->label('Карточка 1 — картинка')
                                                        ->directory('home')
                                                        ->image()
                                                        ->previewable(),
                                                ]),

                                                // Карточка 2
                                                Forms\Components\Group::make([
                                                    Forms\Components\TextInput::make('about_trust_feature_2_title')
                                                        ->label('Карточка 2 — заголовок')
                                                        ->maxLength(255),

                                                    Forms\Components\FileUpload::make('about_trust_feature_2_image_path')
                                                        ->label('Карточка 2 — картинка')
                                                        ->directory('home')
                                                        ->image()
                                                        ->previewable(),
                                                ]),

                                                // Карточка 3
                                                Forms\Components\Group::make([
                                                    Forms\Components\TextInput::make('about_trust_feature_3_title')
                                                        ->label('Карточка 3 — заголовок')
                                                        ->maxLength(255),

                                                    Forms\Components\FileUpload::make('about_trust_feature_3_image_path')
                                                        ->label('Карточка 3 — картинка')
                                                        ->directory('home')
                                                        ->image()
                                                        ->previewable(),
                                                ]),
                                            ]),
                                    ]),

                                Forms\Components\Section::make('Сообщение')
                                    ->schema([
                                        Forms\Components\TextInput::make('about_motto')
                                            ->label('Фраза под карточками')
                                            ->maxLength(255),
                                    ]),

                                Forms\Components\Section::make('Изображения')
                                    ->schema([
                                        Forms\Components\FileUpload::make('about_left_image_path')
                                            ->label('Левое изображение')
                                            ->image()
                                            ->directory('home')
                                            ->imageEditor(),
                                        Forms\Components\FileUpload::make('about_right_image_path')
                                            ->label('Правое изображение')
                                            ->image()
                                            ->directory('home')
                                            ->imageEditor(),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Мы в цифрах')
                                    ->schema([
                                        Forms\Components\TextInput::make('stats_title')
                                            ->label('Заголовок')
                                            ->maxLength(255),
                                    ]),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('stats_item_1_value')
                                            ->label('Значение 1'),
                                        Forms\Components\TextInput::make('stats_item_1_label')
                                            ->label('Подпись 1'),
                                        Forms\Components\Textarea::make('stats_item_1_text')
                                            ->label('Текст 1')
                                            ->rows(2),

                                        Forms\Components\TextInput::make('stats_item_2_value')
                                            ->label('Значение 2'),
                                        Forms\Components\TextInput::make('stats_item_2_label')
                                            ->label('Подпись 2'),
                                        Forms\Components\Textarea::make('stats_item_2_text')
                                            ->label('Текст 2')
                                            ->rows(2),

                                        Forms\Components\TextInput::make('stats_item_3_value')
                                            ->label('Значение 3'),
                                        Forms\Components\TextInput::make('stats_item_3_label')
                                            ->label('Подпись 3'),
                                        Forms\Components\Textarea::make('stats_item_3_text')
                                            ->label('Текст 3')
                                            ->rows(2),
                                    ]),

                                Forms\Components\Section::make('Кнопка внизу')
                                    ->schema([
                                        Forms\Components\TextInput::make('about_more_button_label')
                                            ->label('Текст кнопки')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('about_more_button_url')
                                            ->label('Ссылка кнопки')
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Товары')
                            ->schema([
                                Forms\Components\Section::make('Категории товаров')
                                    ->schema([
                                        Forms\Components\Repeater::make('categories_data')
                                            ->label('Категории для отображения на главной странице')
                                            ->schema([
                                                Forms\Components\Select::make('category_id')
                                                    ->label('Категория')
                                                    ->options(function ($get, $livewire, $state) {
                                                        // Получаем все выбранные категории из других элементов Repeater
                                                        $allItems = $get('../../categories_data') ?? [];
                                                        $currentCategoryId = $state;
                                                        $selectedIds = collect($allItems)
                                                            ->pluck('category_id')
                                                            ->filter()
                                                            ->reject(fn ($id) => $id == $currentCategoryId) // Не исключаем текущую категорию
                                                            ->all();

                                                        // Исключаем уже выбранные категории (кроме текущей)
                                                        return \App\Models\Category::query()
                                                            ->whereNotIn('id', $selectedIds)
                                                            ->pluck('name', 'id')
                                                            ->toArray();
                                                    })
                                                    ->searchable()
                                                    ->required()
                                                    ->live()
                                                    ->helperText('Выберите категорию. Товары из дочерних категорий также будут включены. Порядок элементов определяет порядок отображения на главной странице.'),
                                            ])
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(function (array $state): ?string {
                                                if (empty($state['category_id'])) {
                                                    return null;
                                                }

                                                $category = \App\Models\Category::find($state['category_id']);

                                                return $category?->name;
                                            })
                                            ->helperText('Перетаскивайте элементы для изменения порядка. Максимум 3 товара будет отображаться из каждой категории.'),
                                    ]),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hero_title')
                    ->label('Заголовок первого экрана')
                    ->limit(50),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime('d.m.Y H:i'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomePageContents::route('/'),
            'edit' => Pages\EditHomePageContent::route('/{record}/edit'),
        ];
    }
}
