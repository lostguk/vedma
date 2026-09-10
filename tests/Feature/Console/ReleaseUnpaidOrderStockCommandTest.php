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

it('ночные флаги отменяют старый payment_pending и не трогают свежий', function (): void {
    $product = Product::factory()->withStock(0)->create();
    $old = Order::factory()->create([
        'status' => 'payment_pending',
        'created_at' => now()->subHours(13),
    ]);
    $fresh = Order::factory()->create([
        'status' => 'payment_pending',
        'created_at' => now()->subHour(),
    ]);
    OrderItem::factory()->create([
        'order_id' => $old->id,
        'product_id' => $product->id,
        'count' => 1,
    ]);
    OrderItem::factory()->create([
        'order_id' => $fresh->id,
        'product_id' => $product->id,
        'count' => 1,
    ]);

    $this->artisan('orders:release-unpaid-stock', [
        '--execute' => true,
        '--include-pending' => true,
        '--min-age' => 12,
    ])->assertSuccessful();

    expect($old->fresh()->status)->toBe('cancelled');
    expect($old->fresh()->stock_released_at)->not->toBeNull();
    expect($fresh->fresh()->status)->toBe('payment_pending');
    expect($fresh->fresh()->stock_released_at)->toBeNull();
    expect($product->fresh()->stock)->toBe(1);
});

it('повторный --execute не накручивает склад', function (): void {
    $product = Product::factory()->withStock(0)->create();
    $order = Order::factory()->create([
        'status' => 'payment_failed',
        'created_at' => now()->subDay(),
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
    $this->artisan('orders:release-unpaid-stock', [
        '--execute' => true,
        '--ids' => (string) $order->id,
    ])->assertSuccessful();

    expect($product->fresh()->stock)->toBe(2);
});
