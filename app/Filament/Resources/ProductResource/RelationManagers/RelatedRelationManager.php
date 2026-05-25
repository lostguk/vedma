<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RelatedRelationManager extends RelationManager
{
    protected static string $relationship = 'related';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->inverseRelationship('relatedToProducts')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Добавить товар')
                    ->recordSelectSearchColumns(['name'])
                    ->preloadRecordSelect()
                    ->multiple()
                    ->recordSelectOptionsQuery(
                        fn ($query) => $query->whereKeyNot($this->getOwnerRecord()->getKey())
                    ),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Удалить'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Удалить'),
                ]),
            ]);
    }
}
