<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Payment;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Платежи';

    protected static ?string $label = 'Платеж';

    protected static ?string $pluralLabel = 'Платежи';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('public_id')
            ->columns([
                Tables\Columns\TextColumn::make('public_id')->label('ID')->copyable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(static fn (string $state): string => match ($state) {
                        Payment::STATUS_CREATED => 'Создан',
                        Payment::STATUS_REGISTERED => 'Зарегистрирован',
                        Payment::STATUS_PENDING => 'В ожидании',
                        Payment::STATUS_PAID => 'Оплачен',
                        Payment::STATUS_FAILED => 'Ошибка',
                        Payment::STATUS_REFUNDED => 'Возврат',
                        default => $state,
                    })
                    ->color(static fn (string $state): string => match ($state) {
                        Payment::STATUS_CREATED, Payment::STATUS_REGISTERED, Payment::STATUS_PENDING => 'warning',
                        Payment::STATUS_PAID => 'success',
                        Payment::STATUS_FAILED => 'danger',
                        Payment::STATUS_REFUNDED => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('amount')->label('Сумма')->money('RUB'),
                Tables\Columns\TextColumn::make('external_order_id')->label('OrderId')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_url')->label('Ссылка')->url(fn ($state) => $state)->openUrlInNewTab()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('paid_at')->label('Оплачен')->dateTime('d.m.Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Создан')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
