<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('создает платеж и возвращает ссылку на оплату', function (): void {
    Http::fake([
        '*' => Http::response(['orderId' => 'ext-order-1', 'formUrl' => 'https://pay.test/form'], 200),
    ]);

    $order = Order::factory()->create([
        'total_price_without_discount' => 1200,
        'total_price_with_discount' => 1200,
        'total_price' => 1200,
        'delivery_price' => 100,
    ]);

    $response = $this->postJson('/api/v1/payments', [
        'order_id' => $order->id,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.payment_url', 'https://pay.test/form');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request): bool {
        return str_contains($request->url(), '/payment/rest/register.do')
            && $request['amount'] === 130000;
    });

    $this->assertDatabaseHas('payments', [
        'order_id' => $order->id,
        'amount' => 1300,
        'external_order_id' => 'ext-order-1',
    ]);
});

it('обновляет статус платежа через API статуса', function (): void {
    Http::fake([
        '*' => Http::response(['orderStatus' => 2], 200),
    ]);

    $order = Order::factory()->create();
    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'external_order_id' => 'ext-order-2',
        'status' => Payment::STATUS_PENDING,
    ]);

    $response = $this->getJson("/api/v1/payments/{$payment->public_id}/status");

    $response->assertOk()
        ->assertJsonPath('data.status', Payment::STATUS_PAID);

    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => Payment::STATUS_PAID,
    ]);
});

it('выполняет возврат платежа', function (): void {
    Http::fake([
        '*' => Http::response(['errorCode' => 0], 200),
    ]);

    $order = Order::factory()->create();
    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'external_order_id' => 'ext-order-3',
        'status' => Payment::STATUS_PAID,
    ]);

    $response = $this->postJson("/api/v1/payments/{$payment->public_id}/refund", [
        'amount' => 100.00,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.status', Payment::STATUS_REFUNDED);

    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => Payment::STATUS_REFUNDED,
    ]);
});

it('обрабатывает webhook статуса оплаты', function (): void {
    $order = Order::factory()->create();
    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'external_order_id' => 'ext-order-4',
        'status' => Payment::STATUS_PENDING,
    ]);

    $response = $this->postJson('/api/v1/payments/alfabank/webhook', [
        'orderId' => 'ext-order-4',
        'orderStatus' => 2,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.status', Payment::STATUS_PAID);

    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => Payment::STATUS_PAID,
    ]);
});

it('создает платеж по сумме со скидкой и с учетом доставки', function (): void {
    Http::fake([
        '*' => Http::response(['orderId' => 'ext-order-discount', 'formUrl' => 'https://pay.test/form-discount'], 200),
    ]);

    $category = Category::factory()->create();
    $product = Product::factory()->create(['price' => 1000.00]);
    $product->categories()->attach($category->id);

    $promo = PromoCode::factory()->create([
        'discount_type' => 'percent',
        'discount_value' => 20,
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
    $promo->categories()->attach($category->id);

    $orderResponse = $this->postJson('/api/v1/order', [
        'items' => [
            ['id' => $product->id, 'count' => 1],
        ],
        'promo_code' => $promo->code,
        'register' => false,
        'delivery_type' => 'PostOffice',
        'delivery_price' => 100,
        'first_name' => 'Иван',
        'last_name' => 'Иванов',
        'email' => 'payment-discount@example.com',
        'address' => 'Some Address',
    ]);

    $orderResponse->assertCreated();
    $orderId = (int) $orderResponse->json('data.id');

    $response = $this->postJson('/api/v1/payments', [
        'order_id' => $orderId,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.payment_url', 'https://pay.test/form-discount');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request): bool {
        return str_contains($request->url(), '/payment/rest/register.do')
            && $request['amount'] === 90000;
    });

    $this->assertDatabaseHas('orders', [
        'id' => $orderId,
        'total_price_without_discount' => 1000,
        'total_price_with_discount' => 800,
        'total_price' => 800,
        'delivery_price' => 100,
    ]);

    $this->assertDatabaseHas('payments', [
        'order_id' => $orderId,
        'amount' => 900,
        'external_order_id' => 'ext-order-discount',
    ]);
});

it('берет для оплаты сумму со скидкой, а не без скидки', function (): void {
    Http::fake([
        '*' => Http::response(['orderId' => 'ext-order-price-check', 'formUrl' => 'https://pay.test/form-check'], 200),
    ]);

    $order = Order::factory()->create([
        'total_price_without_discount' => 1000,
        'total_price_with_discount' => 700,
        'total_price' => 700,
        'delivery_price' => 50,
    ]);

    $response = $this->postJson('/api/v1/payments', [
        'order_id' => $order->id,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.payment_url', 'https://pay.test/form-check');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request): bool {
        return str_contains($request->url(), '/payment/rest/register.do')
            && $request['amount'] === 75000;
    });

    $this->assertDatabaseHas('payments', [
        'order_id' => $order->id,
        'amount' => 750,
        'external_order_id' => 'ext-order-price-check',
    ]);
});
