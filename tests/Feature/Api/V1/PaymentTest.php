<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('создает платеж и возвращает ссылку на оплату', function (): void {
    Http::fake([
        '*' => Http::response(['orderId' => 'ext-order-1', 'formUrl' => 'https://pay.test/form'], 200),
    ]);

    $order = Order::factory()->create([
        'total_price' => 1200.00,
        'delivery_price' => 100.00,
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
        'amount' => 1300.0,
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
