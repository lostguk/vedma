<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Services\Export\StockExcelExporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportStock')
                ->label('Выгрузить склад')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $exporter = app(StockExcelExporter::class);
                    $filePath = $exporter->export();
                    $fileName = 'stock-'.now()->format('Y-m-d').'.xlsx';

                    return response()->download($filePath, $fileName, [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])->deleteFileAfterSend();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
