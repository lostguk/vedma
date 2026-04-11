<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Export\OrderExcelExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

uses(RefreshDatabase::class);

it('adds photo column, embeds product image and keeps fallback placeholder', function () {
    $order = Order::factory()->create([
        'total_price' => 3500,
        'total_price_without_discount' => 3500,
        'total_price_with_discount' => 3500,
        'delivery_price' => 0,
        'status' => 'new',
    ]);

    $productWithImage = Product::factory()->create(['name' => 'Товар с фото']);
    $productWithoutImage = Product::factory()->create(['name' => 'Товар без фото']);
    $productWithoutImage->clearMediaCollection(Product::IMAGES_COLLECTION);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $productWithImage->id,
        'name' => $productWithImage->name,
        'price' => 1000,
        'count' => 2,
        'total' => 2000,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $productWithoutImage->id,
        'name' => $productWithoutImage->name,
        'price' => 1500,
        'count' => 1,
        'total' => 1500,
    ]);

    $exporter = app(OrderExcelExporter::class);
    $filePath = $exporter->export($order->fresh());

    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $headerRow = findRowByValue($sheet, 'Фото');
        expect($headerRow)->not()->toBeNull();

        $firstItemRow = $headerRow + 1;
        $secondItemRow = $firstItemRow + 1;

        $hasDrawingForFirstItem = false;
        foreach ($sheet->getDrawingCollection() as $drawing) {
            if ($drawing->getCoordinates() === "C{$firstItemRow}") {
                $hasDrawingForFirstItem = true;
                break;
            }
        }

        expect($hasDrawingForFirstItem)->toBeTrue();
        expect($sheet->getCell("C{$secondItemRow}")->getValue())->toBe('—');
    } finally {
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
});

function findRowByValue(Worksheet $sheet, string $value): ?int
{
    for ($row = 1; $row <= 100; $row++) {
        for ($column = 'A'; $column <= 'F'; $column++) {
            if ($sheet->getCell("{$column}{$row}")->getValue() === $value) {
                return $row;
            }
        }
    }

    return null;
}
