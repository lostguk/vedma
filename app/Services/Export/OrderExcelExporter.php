<?php

declare(strict_types=1);

namespace App\Services\Export;

use App\Models\Order;
use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class OrderExcelExporter
{
    public function export(Order $order): string
    {
        $order->loadMissing(['items.product.media', 'promoCode']);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Заказ #{$order->id}");

        $headerFill = [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF7B5EA7'],
        ];
        $headerFont = ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11];
        $titleFont = ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF2D2640']];
        $labelFont = ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FF564D6B']];
        $borderThin = ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD6CCE4']];

        $row = 1;

        $sheet->setCellValue("A{$row}", "Заказ #{$order->id}");
        $sheet->getStyle("A{$row}")->getFont()->applyFromArray($titleFont);
        $sheet->mergeCells("A{$row}:F{$row}");
        $row++;

        $date = $order->created_at?->format('d.m.Y H:i') ?? '';
        $sheet->setCellValue("A{$row}", "Дата: {$date}");
        $sheet->getStyle("A{$row}")->getFont()->setSize(10)->setColor(new Color('FF8A7FA0'));
        $row += 2;

        $sheet->setCellValue("A{$row}", 'ИНФОРМАЦИЯ О КЛИЕНТЕ');
        $sheet->getStyle("A{$row}")->getFont()->applyFromArray($labelFont);
        $sheet->mergeCells("A{$row}:F{$row}");
        $row++;

        $clientInfo = [
            'ФИО' => implode(' ', array_filter([$order->last_name, $order->first_name, $order->middle_name])),
            'Email' => $order->email,
            'Телефон' => $order->phone ?? '—',
            'Адрес' => $order->address ?? '—',
            'Статус' => $this->statusLabel($order->status),
        ];

        foreach ($clientInfo as $label => $value) {
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("C{$row}", $value);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $sheet->mergeCells("C{$row}:F{$row}");
            $row++;
        }

        $row++;

        $sheet->setCellValue("A{$row}", 'ТОВАРЫ');
        $sheet->getStyle("A{$row}")->getFont()->applyFromArray($labelFont);
        $row++;

        $headers = ['№', 'Наименование', 'Фото', 'Цена', 'Кол-во', 'Сумма'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($cols as $i => $col) {
            $sheet->setCellValue("{$col}{$row}", $headers[$i]);
            $sheet->getStyle("{$col}{$row}")->getFont()->applyFromArray($headerFont);
            $sheet->getStyle("{$col}{$row}")->getFill()->applyFromArray($headerFill);
            $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        $row++;

        $itemNum = 1;
        foreach ($order->items as $item) {
            $sheet->setCellValue("A{$row}", $itemNum);
            $sheet->setCellValue("B{$row}", $item->name);
            $sheet->setCellValue("D{$row}", number_format((float) $item->price, 0, ',', ' ').' ₽');
            $sheet->setCellValue("E{$row}", $item->count);
            $sheet->setCellValue("F{$row}", number_format((float) ($item->total ?? $item->price * $item->count), 0, ',', ' ').' ₽');

            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getRowDimension($row)->setRowHeight(105);

            $media = $item->product?->getFirstMedia(Product::IMAGES_COLLECTION);
            $imagePath = $media instanceof Media ? $this->resolveMediaPath($media) : null;

            if ($imagePath !== null) {
                $drawing = new Drawing;
                $drawing->setName("Фото товара {$itemNum}");
                $drawing->setDescription($item->name);
                $drawing->setPath($imagePath);
                $drawing->setHeight(140);
                $drawing->setCoordinates("C{$row}");
                $drawing->setOffsetX(8);
                $drawing->setOffsetY(4);
                $drawing->setWorksheet($sheet);
            } else {
                $sheet->setCellValue("C{$row}", '—');
                $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            foreach ($cols as $col) {
                $sheet->getStyle("{$col}{$row}")->getBorders()->getBottom()->applyFromArray($borderThin);
            }

            $itemNum++;
            $row++;
        }

        $row++;

        $totalWithout = $order->total_price_without_discount ?? $order->total_price;
        $totalWith = $order->total_price_with_discount ?? $order->total_price;
        $delivery = $order->delivery_price ?? 0;

        $sheet->setCellValue("E{$row}", 'Товары:');
        $sheet->setCellValue("F{$row}", number_format((float) $totalWithout, 0, ',', ' ').' ₽');
        $sheet->getStyle("E{$row}")->getFont()->setBold(true);
        $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $row++;

        if ($order->promoCode) {
            $discount = $totalWithout - $totalWith;
            $sheet->setCellValue("E{$row}", "Промокод ({$order->promoCode->code}):");
            $sheet->setCellValue("F{$row}", '-'.number_format((float) $discount, 0, ',', ' ').' ₽');
            $sheet->getStyle("F{$row}")->getFont()->setColor(new Color('FFD64545'));
            $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $row++;
        }

        if ($delivery > 0) {
            $sheet->setCellValue("E{$row}", 'Доставка:');
            $sheet->setCellValue("F{$row}", number_format((float) $delivery, 0, ',', ' ').' ₽');
            $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $row++;
        }

        $grandTotal = $totalWith + $delivery;
        $sheet->setCellValue("E{$row}", 'ИТОГО:');
        $sheet->setCellValue("F{$row}", number_format((float) $grandTotal, 0, ',', ' ').' ₽');
        $sheet->getStyle("E{$row}:F{$row}")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(24);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(16);

        $tempFile = tempnam(sys_get_temp_dir(), 'order_').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return $tempFile;
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'new' => 'Новый',
            'payment_pending' => 'Ожидает оплату',
            'payment_failed' => 'Ошибка оплаты',
            'paid' => 'Оплачен',
            'refunded' => 'Возврат',
            'cancelled' => 'Отменён',
            default => $status,
        };
    }

    private function resolveMediaPath(Media $media): ?string
    {
        $thumbPath = $media->getPath('thumb');
        if ($thumbPath !== '' && is_file($thumbPath)) {
            return $thumbPath;
        }

        $originalPath = $media->getPath();
        if ($originalPath !== '' && is_file($originalPath)) {
            return $originalPath;
        }

        return null;
    }
}
