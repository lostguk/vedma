<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Каталог';

    protected static ?string $navigationLabel = 'Товары';

    protected static ?string $modelLabel = 'товар';

    protected static ?string $pluralModelLabel = 'товары';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description')
                            ->label('Описание')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('price')
                            ->label('Цена')
                            ->required()
                            ->numeric()
                            ->prefix('₽'),
                        Forms\Components\TextInput::make('old_price')
                            ->label('Старая цена')
                            ->numeric()
                            ->prefix('₽'),
                    ])->columns(2),

                Forms\Components\Section::make('Характеристики')
                    ->schema([
                        Forms\Components\TextInput::make('weight')
                            ->label('Вес (г)')
                            ->numeric()
                            ->suffix('г'),
                        Forms\Components\TextInput::make('width')
                            ->label('Ширина (см)')
                            ->numeric()
                            ->suffix('см'),
                        Forms\Components\TextInput::make('height')
                            ->label('Высота (см)')
                            ->numeric()
                            ->suffix('см'),
                        Forms\Components\TextInput::make('length')
                            ->label('Длина (см)')
                            ->numeric()
                            ->suffix('см'),
                    ])->columns(2),

                Forms\Components\Section::make('Изображения')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection(Product::IMAGES_COLLECTION)
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->maxSize(1024 * 2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Настройки')
                    ->schema([
                        Forms\Components\Toggle::make('is_new')
                            ->label('Новинка')
                            ->default(false),
                        Forms\Components\Toggle::make('is_bestseller')
                            ->label('Хит продаж')
                            ->default(false),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Сортировка')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),

                Forms\Components\Section::make('Категории')
                    ->schema([
                        Forms\Components\Select::make('categories')
                            ->label('Категории')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->label('Изображение')
                    ->collection(Product::IMAGES_COLLECTION)
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('old_price')
                    ->label('Старая цена')
                    ->money('RUB')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_new')
                    ->label('Новинка')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_bestseller')
                    ->label('Хит продаж')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Сортировка')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлен')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_new')
                    ->label('Новинка'),
                Tables\Filters\TernaryFilter::make('is_bestseller')
                    ->label('Хит продаж'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('filament.actions.edit.label')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('filament.actions.delete.label')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('filament.actions.delete.label')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
