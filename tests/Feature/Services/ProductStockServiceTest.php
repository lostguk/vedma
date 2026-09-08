<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\ProductStockService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('возвращает остатки один раз и ставит stock_released_at', function (): void {
    $product = Product::factory()->withStock(1)->create();
    $order = Order::factory()->create(['status' => 'payment_failed']);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'count' => 2,
    ]);

    $service = app(ProductStockService::class);

    expect($service->restore($order))->toBeTrue();
    expect($product->fresh()->stock)->toBe(3);
    expect($order->fresh()->stock_released_at)->not->toBeNull();

    expect($service->restore($order->fresh()))->toBeFalse();
    expect($product->fresh()->stock)->toBe(3);
});

it('отменяет неоплаченный заказ и возвращает склад', function (): void {
    $product = Product::factory()->withStock(0)->create();
    $order = Order::factory()->create(['status' => 'payment_failed']);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'count' => 1,
    ]);

    app(ProductStockService::class)->cancelAndRelease($order);

    $order->refresh();
    expect($order->status)->toBe('cancelled');
    expect($order->stock_released_at)->not->toBeNull();
    expect($product->fresh()->stock)->toBe(1);
});

it('не отменяет оплаченный заказ', function (): void {
    $order = Order::factory()->create(['status' => 'paid']);

    app(ProductStockService::class)->cancelAndRelease($order);
})->throws(\RuntimeException::class, 'Нельзя отменить оплаченный заказ. Оформите возврат оплаты.');
