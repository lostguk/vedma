<?php

declare(strict_types=1);

namespace App\Services\Export;

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class StockExcelExporter
{
    public function export(): string
    {
        $products = Product::query()
            ->with('categories')
            ->orderBy('sort_order')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Склад');

        $headerFill = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF7B5EA7']];
        $headerFont = ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10];
        $titleFont = ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF2D2640']];
        $borderThin = ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD6CCE4']];

        $row = 1;
        $sheet->setCellValue("A{$row}", 'Остатки на складе');
        $sheet->getStyle("A{$row}")->getFont()->applyFromArray($titleFont);
        $sheet->mergeCells("A{$row}:G{$row}");
        $row++;

        $date = now()->format('d.m.Y H:i');
        $sheet->setCellValue("A{$row}", "Дата выгрузки: {$date}");
        $sheet->getStyle("A{$row}")->getFont()->setSize(9)->setColor(new Color('FF8A7FA0'));
        $row += 2;

        $headers = ['ID', 'Наименование', 'Категории', 'Цена', 'Остаток', 'Статус', 'Обновлён'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        foreach ($cols as $i => $col) {
            $sheet->setCellValue("{$col}{$row}", $headers[$i]);
            $sheet->getStyle("{$col}{$row}")->getFont()->applyFromArray($headerFont);
            $sheet->getStyle("{$col}{$row}")->getFill()->applyFromArray($headerFill);
            $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        $row++;

        $totalProducts = 0;
        $totalInStock = 0;
        $totalOutOfStock = 0;
        $totalUnlimited = 0;

        foreach ($products as $product) {
            $categories = $product->categories->pluck('name')->join(', ') ?: '—';
            $stockText = $product->stock === null ? '∞' : (string) $product->stock;
            $status = $product->stock === null ? 'Безлимит' : ($product->stock > 0 ? 'В наличии' : 'Нет в наличии');

            $sheet->setCellValue("A{$row}", $product->id);
            $sheet->setCellValue("B{$row}", $product->name);
            $sheet->setCellValue("C{$row}", $categories);
            $sheet->setCellValue("D{$row}", number_format((float) $product->price, 0, ',', ' ') . ' ₽');
            $sheet->setCellValue("E{$row}", $stockText);
            $sheet->setCellValue("F{$row}", $status);
            $sheet->setCellValue("G{$row}", $product->updated_at?->format('d.m.Y'));

            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            if ($product->stock === 0) {
                $sheet->getStyle("E{$row}")->getFont()->setColor(new Color('FFD64545'));
                $sheet->getStyle("F{$row}")->getFont()->setColor(new Color('FFD64545'));
            } elseif ($product->stock !== null && $product->stock <= 5) {
                $sheet->getStyle("E{$row}")->getFont()->setColor(new Color('FFC2703E'));
                $sheet->getStyle("F{$row}")->getFont()->setColor(new Color('FFC2703E'));
            }

            foreach ($cols as $col) {
                $sheet->getStyle("{$col}{$row}")->getBorders()->getBottom()->applyFromArray($borderThin);
            }

            $totalProducts++;
            if ($product->stock === null) {
                $totalUnlimited++;
            } elseif ($product->stock > 0) {
                $totalInStock++;
            } else {
                $totalOutOfStock++;
            }

            $row++;
        }

        $row += 2;
        $sheet->setCellValue("A{$row}", 'ИТОГО');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(11);
        $row++;

        $summary = [
            'Всего товаров:' => $totalProducts,
            'В наличии:' => $totalInStock,
            'Нет в наличии:' => $totalOutOfStock,
            'Безлимитных (услуги):' => $totalUnlimited,
        ];

        foreach ($summary as $label => $value) {
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("B{$row}", $value);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(16);
        $sheet->getColumnDimension('G')->setWidth(14);

        $tempFile = tempnam(sys_get_temp_dir(), 'stock_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return $tempFile;
    }
}
