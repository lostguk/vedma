<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Payment\OrderBundleBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config([
        'services.alfabank.fiscal.enabled' => true,
        'services.alfabank.fiscal.tax_system' => 1,
        'services.alfabank.fiscal.default_tax_type' => 10,
        'services.alfabank.fiscal.payment_method' => 4,
        'services.alfabank.fiscal.payment_object' => 1,
        'services.alfabank.fiscal.delivery_payment_object' => 4,
        'services.alfabank.fiscal.delivery_name' => 'Доставка',
    ]);
});

it('возвращает null когда фискализация отключена', function (): void {
    config(['services.alfabank.fiscal.enabled' => false]);

    $order = Order::factory()->create();
    $builder = new OrderBundleBuilder;
    $result = $builder->build($order);

    expect($result)->toBeNull();
});

it('формирует orderBundle с позициями заказа', function (): void {
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

    $builder = new OrderBundleBuilder;
    $result = $builder->build($order);

    expect($result)->not->toBeNull()
        ->and($result['taxSystem'])->toBe(1)
        ->and($result['email'])->toBe('test@example.com')
        ->and($result['orderBundle']['cartItems']['items'])->toHaveCount(1);

    $item = $result['orderBundle']['cartItems']['items'][0];
    expect($item['positionId'])->toBe(1)
        ->and($item['name'])->toBe('Магический кристалл')
        ->and($item['quantity']['value'])->toBe(2)
        ->and($item['itemPrice'])->toBe(50000)
        ->and($item['itemAmount'])->toBe(100000)
        ->and($item['itemCode'])->toBe((string) $product->id)
        ->and($item['tax']['taxType'])->toBe(10)
        ->and($item['itemAttributes']['attributes'])->toContain(
            ['name' => 'paymentMethod', 'value' => '4'],
            ['name' => 'paymentObject', 'value' => '1'],
        );
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

    $builder = new OrderBundleBuilder;
    $result = $builder->build($order);

    $items = $result['orderBundle']['cartItems']['items'];
    expect($items)->toHaveCount(2);

    $deliveryItem = $items[1];
    expect($deliveryItem['name'])->toBe('Доставка')
        ->and($deliveryItem['itemPrice'])->toBe(15000)
        ->and($deliveryItem['itemAmount'])->toBe(15000)
        ->and($deliveryItem['quantity']['value'])->toBe(1)
        ->and($deliveryItem['itemCode'])->toBe('delivery')
        ->and($deliveryItem['itemAttributes']['attributes'])->toContain(
            ['name' => 'paymentObject', 'value' => '4'],
        );
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

    $builder = new OrderBundleBuilder;
    $result = $builder->build($order);

    expect($result['orderBundle']['cartItems']['items'])->toHaveCount(1);
});

it('включает email и phone в customerDetails', function (): void {
    $product = Product::factory()->create(['price' => 100.00]);
    $order = Order::factory()->create([
        'email' => 'customer@example.com',
        'phone' => '+7 (999) 123-45-67',
        'total_price' => 100,
        'total_price_with_discount' => 100,
        'delivery_price' => 0,
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name' => 'Товар',
        'price' => 100.00,
        'count' => 1,
        'total' => 100.00,
    ]);

    $builder = new OrderBundleBuilder;
    $result = $builder->build($order);

    $customer = $result['orderBundle']['customerDetails'];
    expect($customer['email'])->toBe('customer@example.com')
        ->and($customer['phone'])->toBe('+79991234567');
});

it('корректирует сумму позиций при расхождении с total_price (промокод с округлением)', function (): void {
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

    $builder = new OrderBundleBuilder;
    $result = $builder->build($order);
    $items = $result['orderBundle']['cartItems']['items'];

    $totalItemAmounts = 0;
    foreach ($items as $item) {
        $totalItemAmounts += $item['itemAmount'];
    }

    $expectedAmount = (533 + 100) * 100;
    expect($totalItemAmounts)->toBe($expectedAmount);
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

    $builder = new OrderBundleBuilder;
    $result = $builder->build($order);
    $items = $result['orderBundle']['cartItems']['items'];

    $totalItemAmounts = 0;
    foreach ($items as $item) {
        $totalItemAmounts += $item['itemAmount'];
    }

    $expectedAmount = (799 + 150) * 100;
    expect($totalItemAmounts)->toBe($expectedAmount)
        ->and($items)->toHaveCount(2)
        ->and($items[0]['itemAmount'])->toBe(79900)
        ->and($items[1]['itemAmount'])->toBe(15000);
});

it('корректно рассчитывает налог НДС 5%', function (): void {
    config(['services.alfabank.fiscal.default_tax_type' => 10]);

    $product = Product::factory()->create(['price' => 1000.00]);
    $order = Order::factory()->create([
        'email' => 'tax@example.com',
        'total_price' => 1000,
        'total_price_with_discount' => 1000,
        'delivery_price' => 0,
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name' => 'Товар',
        'price' => 1000.00,
        'count' => 1,
        'total' => 1000.00,
    ]);

    $builder = new OrderBundleBuilder;
    $result = $builder->build($order);

    $taxSum = $result['orderBundle']['cartItems']['items'][0]['tax']['taxSum'];
    expect($taxSum)->toBe(5000);
});
