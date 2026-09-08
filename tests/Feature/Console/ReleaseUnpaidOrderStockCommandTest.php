<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('в dry-run не меняет склад и статусы', function (): void {
    $product = Product::factory()->withStock(0)->create();
    $order = Order::factory()->create([
        'status' => 'payment_failed',
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'count' => 1,
    ]);

    $this->artisan('orders:release-unpaid-stock')
        ->assertSuccessful();

    expect($order->fresh()->status)->toBe('payment_failed');
    expect($order->fresh()->stock_released_at)->toBeNull();
    expect($product->fresh()->stock)->toBe(0);
});

it('с --execute отменяет свежий неоплаченный заказ и возвращает склад', function (): void {
    $product = Product::factory()->withStock(0)->create();
    $order = Order::factory()->create([
        'status' => 'payment_failed',
        'created_at' => now(),
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'count' => 1,
    ]);

    $this->artisan('orders:release-unpaid-stock', [
        '--execute' => true,
        '--ids' => (string) $order->id,
    ])->assertSuccessful();

    $order->refresh();
    expect($order->status)->toBe('cancelled');
    expect($order->stock_released_at)->not->toBeNull();
    expect($product->fresh()->stock)->toBe(1);
});

it('для исторических refunded только ставит флаг без инкремента', function (): void {
    $product = Product::factory()->withStock(5)->create();
    $order = Order::factory()->create([
        'status' => 'refunded',
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'count' => 2,
    ]);

    $this->artisan('orders:release-unpaid-stock', [
        '--execute' => true,
        '--ids' => (string) $order->id,
    ])->assertSuccessful();

    expect($order->fresh()->stock_released_at)->not->toBeNull();
    expect($product->fresh()->stock)->toBe(5);
});

it('с --min-age не берёт более свежие заказы', function (): void {
    $product = Product::factory()->withStock(0)->create();
    $order = Order::factory()->create([
        'status' => 'payment_failed',
        'created_at' => now()->subHour(),
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'count' => 1,
    ]);

    $this->artisan('orders:release-unpaid-stock', [
        '--min-age' => 24,
        '--execute' => true,
    ])->assertSuccessful();

    expect($order->fresh()->status)->toBe('payment_failed');
    expect($order->fresh()->stock_released_at)->toBeNull();
    expect($product->fresh()->stock)->toBe(0);
});
