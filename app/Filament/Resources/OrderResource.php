<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Каталог';

    protected static ?string $navigationLabel = 'Заказы';

    protected static ?string $modelLabel = 'заказ';

    protected static ?string $pluralModelLabel = 'заказы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Пользователь')
                    ->schema([
                        Select::make('user_id')
                            ->label('Пользователь')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->nullable()
                            ->disabled(),
                    ])->columns(1),
                Section::make('Данные пользователя')
                    ->schema([
                        TextInput::make('first_name')->label('Имя')->required(),
                        TextInput::make('last_name')->label('Фамилия')->required(),
                        TextInput::make('middle_name')->label('Отчество'),
                        TextInput::make('email')->label('Email')->email()->required(),
                        TextInput::make('phone')->label('Телефон'),
                    ])->columns(2),
                Section::make('Адрес доставки')
                    ->schema([
                        TextInput::make('country')->label('Страна'),
                        TextInput::make('region')->label('Регион'),
                        TextInput::make('city')->label('Город'),
                        TextInput::make('postal_code')->label('Индекс'),
                        TextInput::make('address')->label('Адрес'),
                    ])->columns(2),
                Section::make('Доставка')
                    ->schema([
                        Select::make('delivery_type')
                            ->label('Тип доставки')
                            ->options([
                                'pickup' => 'Самовывоз',
                                'courier' => 'Курьер',
                                'post' => 'Почта',
                            ])->nullable(),
                        TextInput::make('delivery_price')->label('Стоимость доставки')->numeric()->nullable()->readOnly()->disabled(),
                        Select::make('delivery_status')
                            ->label('Статус доставки')
                            ->options([
                                'pending' => 'Ожидает',
                                'shipped' => 'Отправлен',
                                'delivered' => 'Доставлен',
                            ])->nullable(),
                        Textarea::make('delivery_data')->label('Данные доставки')->nullable(),
                    ])->columns(2),
                Section::make('Детали заказа')
                    ->schema([
                        Select::make('promo_code_id')
                            ->label('Промокод')
                            ->relationship('promoCode', 'code')
                            ->searchable()
                            ->nullable()
                            ->disabled(),
                        TextInput::make('total_price')->label('Сумма заказа')->numeric()->disabled(),
                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'new' => 'Новый',
                                'processing' => 'В обработке',
                                'paid' => 'Оплачен',
                                'cancelled' => 'Отменён',
                            ])->required()
                            ->disabled(),
                        Select::make('payment_type')
                            ->label('Тип оплаты')
                            ->options([
                                'card' => 'Карта',
                                'cash' => 'Наличные',
                                'online' => 'Онлайн',
                            ])->nullable()
                            ->disabled(),
                        DateTimePicker::make('paid_at')->label('Оплачен в')->nullable()->disabled(),
                        Textarea::make('comment')->label('Комментарий'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('user.id')->label('ID пользователя')->sortable(),
                Tables\Columns\TextColumn::make('user.email')->label('Email пользователя')->searchable(),
                Tables\Columns\TextColumn::make('first_name')->label('Имя'),
                Tables\Columns\TextColumn::make('last_name')->label('Фамилия'),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('total_price')->label('Сумма')->money('RUB')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Статус')->badge()->sortable(),
                Tables\Columns\TextColumn::make('payment_type')->label('Оплата'),
                Tables\Columns\TextColumn::make('delivery_type')->label('Доставка'),
                Tables\Columns\TextColumn::make('created_at')->label('Создан')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
