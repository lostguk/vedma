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

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Слайдер')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Section::make('Слайды на главной странице')
                                    ->description('Для управления слайдами перейдите в раздел «Слайдер на главной» в меню «Контент».')
                                    ->schema([
                                        Forms\Components\Placeholder::make('slides_info')
                                            ->label('')
                                            ->content(function () {
                                                $count = \App\Models\HeroSlide::where('is_active', true)->count();
                                                $total = \App\Models\HeroSlide::count();

                                                return "Активных слайдов: {$count} из {$total}";
                                            }),
                                        Forms\Components\Actions::make([
                                            Forms\Components\Actions\Action::make('manage_slides')
                                                ->label('Управление слайдами')
                                                ->icon('heroicon-o-arrow-top-right-on-square')
                                                ->url(HeroSlideResource::getUrl('index'))
                                                ->openUrlInNewTab(false),
                                        ]),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Товары')
                            ->icon('heroicon-o-shopping-bag')
                            ->schema([
                                Forms\Components\Section::make('Категории товаров на главной')
                                    ->description('Выберите категории для отображения на главной странице. Товары из дочерних категорий также будут включены. Перетаскивайте элементы для изменения порядка.')
                                    ->schema([
                                        Forms\Components\Repeater::make('categories_data')
                                            ->label('Категории')
                                            ->schema([
                                                Forms\Components\Select::make('category_id')
                                                    ->label('Категория')
                                                    ->options(function ($get, $livewire, $state) {
                                                        $allItems = $get('../../categories_data') ?? [];
                                                        $currentCategoryId = $state;
                                                        $selectedIds = collect($allItems)
                                                            ->pluck('category_id')
                                                            ->filter()
                                                            ->reject(fn ($id) => $id == $currentCategoryId)
                                                            ->all();

                                                        return \App\Models\Category::query()
                                                            ->whereNotIn('id', $selectedIds)
                                                            ->pluck('name', 'id')
                                                            ->toArray();
                                                    })
                                                    ->searchable()
                                                    ->required()
                                                    ->live(),
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
                                            }),
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
                Tables\Columns\TextColumn::make('categories_count')
                    ->label('Категорий')
                    ->counts('categories'),
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
