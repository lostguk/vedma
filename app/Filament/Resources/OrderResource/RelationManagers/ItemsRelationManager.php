<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Позиции заказа';

    protected static ?string $label = 'Позиция заказа';

    protected static ?string $pluralLabel = 'Позиции заказа';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Позиции заказа';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Товар')
                    ->relationship('product', 'name')
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $product = \App\Models\Product::find($state);
                        if ($product) {
                            $set('price', $product->price);
                            $set('total', $product->price);
                        } else {
                            $set('price', null);
                            $set('total', null);
                        }
                    }),
                Forms\Components\TextInput::make('price')
                    ->label('Цена')
                    ->numeric()
                    ->readOnly(),
                Forms\Components\TextInput::make('count')
                    ->label('Количество')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $price = $get('price') ?? 0;
                        $set('total', $price * (int) $state);
                    }),
                Forms\Components\TextInput::make('total')
                    ->label('Сумма')
                    ->numeric()
                    ->readOnly(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Товар'),
                Tables\Columns\TextColumn::make('price')->label('Цена')->money('RUB'),
                Tables\Columns\TextColumn::make('count')->label('Кол-во'),
                Tables\Columns\TextColumn::make('total')->label('Сумма')->money('RUB'),
            ])
            ->filters([
                //
            ]);
    }
}
