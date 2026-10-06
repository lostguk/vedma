<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Payment\ReceiptBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config([
        'services.tbank.fiscal.enabled' => true,
        'services.tbank.fiscal.taxation' => 'usn_income',
        'services.tbank.fiscal.tax' => 'vat5',
        'services.tbank.fiscal.payment_method' => 'full_payment',
        'services.tbank.fiscal.payment_object' => 'commodity',
        'services.tbank.fiscal.delivery_payment_object' => 'service',
        'services.tbank.fiscal.delivery_name' => 'Доставка',
    ]);
});

it('возвращает null когда фискализация отключена', function (): void {
    config(['services.tbank.fiscal.enabled' => false]);

    $order = Order::factory()->create();
    $result = (new ReceiptBuilder)->build($order);

    expect($result)->toBeNull();
});

it('формирует чек с позициями заказа', function (): void {
    $product = Product::factory()->create(['name' => 'Магический кристалл', 'price' => 500.00]);
    $order = Order::factory()->create([
        'email' => 'test@example.com',
        'phone' => '+79991234567',
        'total_price' => 1000,
        'total_price_with_discount' => 1000,
        'delivery_price' => 0,
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name' => 'Магический кристалл',
        'price' => 500.00,
        'count' => 2,
        'total' => 1000.00,
    ]);

    $result = (new ReceiptBuilder)->build($order);

    expect($result)->not->toBeNull()
        ->and($result['Taxation'])->toBe('usn_income')
        ->and($result['Email'])->toBe('test@example.com')
        ->and($result['Phone'])->toBe('+79991234567')
        ->and($result['Items'])->toHaveCount(1);

    $item = $result['Items'][0];
    expect($item['Name'])->toBe('Магический кристалл')
        ->and($item['Quantity'])->toBe(2)
        ->and($item['Price'])->toBe(50000)
        ->and($item['Amount'])->toBe(100000)
        ->and($item['Tax'])->toBe('vat5')
        ->and($item['PaymentMethod'])->toBe('full_payment')
        ->and($item['PaymentObject'])->toBe('commodity');
});

it('включает доставку как отдельную позицию', function (): void {
    $product = Product::factory()->create(['price' => 300.00]);
    $order = Order::factory()->create([
        'email' => 'delivery@example.com',
        'total_price' => 300,
        'total_price_with_discount' => 300,
        'delivery_price' => 150,
        'delivery_type' => 'PostOffice',
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name' => 'Товар',
        'price' => 300.00,
        'count' => 1,
        'total' => 300.00,
    ]);

    $items = (new ReceiptBuilder)->build($order)['Items'];

    expect($items)->toHaveCount(2);

    $deliveryItem = $items[1];
    expect($deliveryItem['Name'])->toBe('Доставка')
        ->and($deliveryItem['Price'])->toBe(15000)
        ->and($deliveryItem['Amount'])->toBe(15000)
        ->and($deliveryItem['Quantity'])->toBe(1)
        ->and($deliveryItem['PaymentObject'])->toBe('service');
});

it('не включает доставку если цена нулевая', function (): void {
    $product = Product::factory()->create(['price' => 200.00]);
    $order = Order::factory()->create([
        'email' => 'nodelivery@example.com',
        'total_price' => 200,
        'total_price_with_discount' => 200,
        'delivery_price' => 0,
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name' => 'Товар',
        'price' => 200.00,
        'count' => 1,
        'total' => 200.00,
    ]);

    expect((new ReceiptBuilder)->build($order)['Items'])->toHaveCount(1);
});

it('корректирует сумму позиций при расхождении с total_price', function (): void {
    $product1 = Product::factory()->create(['price' => 333.00]);
    $product2 = Product::factory()->create(['price' => 333.00]);

    $order = Order::factory()->create([
        'email' => 'promo@example.com',
        'total_price' => 533,
        'total_price_without_discount' => 666,
        'total_price_with_discount' => 533,
        'delivery_price' => 100,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product1->id,
        'name' => 'Товар 1',
        'price' => 267.00,
        'count' => 1,
        'total' => 267.00,
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product2->id,
        'name' => 'Товар 2',
        'price' => 267.00,
        'count' => 1,
        'total' => 267.00,
    ]);

    $items = (new ReceiptBuilder)->build($order)['Items'];
    $total = 0;
    foreach ($items as $item) {
        $total += $item['Amount'];
        expect($item['Amount'])->toBe($item['Price'] * $item['Quantity']);
    }

    expect($total)->toBe((533 + 100) * 100);
});

it('сумма позиций чека совпадает с суммой платежа при применении промокода', function (): void {
    $product = Product::factory()->create(['price' => 999.00]);

    $order = Order::factory()->create([
        'email' => 'rounding@example.com',
        'total_price' => 799,
        'total_price_without_discount' => 999,
        'total_price_with_discount' => 799,
        'delivery_price' => 150,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name' => 'Товар со скидкой',
        'price' => 799.00,
        'count' => 1,
        'total' => 799.00,
    ]);

    $items = (new ReceiptBuilder)->build($order)['Items'];
    $total = 0;
    foreach ($items as $item) {
        $total += $item['Amount'];
    }

    expect($total)->toBe((799 + 150) * 100)
        ->and($items)->toHaveCount(2)
        ->and($items[0]['Amount'])->toBe(79900)
        ->and($items[1]['Amount'])->toBe(15000);
});
