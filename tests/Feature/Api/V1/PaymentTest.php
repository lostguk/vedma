<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PromoCode;
use App\Services\DaData\AddressSuggestService;
use App\Services\Shipping\ShippingCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function tbankNotification(array $overrides = []): array
{
    $payload = array_merge([
        'TerminalKey' => 'TestTerminal',
        'OrderId' => 'order',
        'Success' => true,
        'Status' => 'CONFIRMED',
        'PaymentId' => '1',
        'ErrorCode' => '0',
        'Amount' => 10000,
    ], $overrides);

    $flat = [];
    foreach ($payload as $key => $value) {
        if (is_array($value) || $value === null) {
            continue;
        }
        $flat[$key] = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
    }
    $flat['Password'] = 'test-password';
    ksort($flat, SORT_STRING);
    $payload['Token'] = hash('sha256', implode('', $flat));

    return $payload;
}

it('создает платеж и возвращает ссылку на оплату', function (): void {
    Http::fake([
        '*' => Http::response(['Success' => true, 'ErrorCode' => '0', 'PaymentId' => 'ext-order-1', 'PaymentURL' => 'https://pay.test/form'], 200),
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
        return str_contains($request->url(), '/Init')
            && $request['Amount'] === 130000;
    });

    $this->assertDatabaseHas('payments', [
        'order_id' => $order->id,
        'amount' => 1300,
        'external_order_id' => 'ext-order-1',
    ]);
});

it('добавляет payment public_id в returnUrl и failUrl при регистрации', function (): void {
    Http::fake([
        '*' => Http::response(['Success' => true, 'ErrorCode' => '0', 'PaymentId' => 'ext-order-return', 'PaymentURL' => 'https://pay.test/form-return'], 200),
    ]);

    $order = Order::factory()->create([
        'total_price_without_discount' => 500,
        'total_price_with_discount' => 500,
        'total_price' => 500,
        'delivery_price' => 0,
    ]);

    $response = $this->postJson('/api/v1/payments', [
        'order_id' => $order->id,
        'success_url' => 'https://shop.test/payment-success?order_id='.$order->id,
        'fail_url' => 'https://shop.test/payment-error?order_id='.$order->id,
    ]);

    $response->assertOk();

    $payment = Payment::query()->where('order_id', $order->id)->first();
    expect($payment)->not->toBeNull();

    Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($payment, $order): bool {
        if (! str_contains($request->url(), '/Init')) {
            return false;
        }

        $returnUrl = (string) ($request['SuccessURL'] ?? '');
        $failUrl = (string) ($request['FailURL'] ?? '');

        return str_contains($returnUrl, 'payment='.$payment->public_id)
            && str_contains($returnUrl, 'order_id='.$order->id)
            && str_contains($failUrl, 'payment='.$payment->public_id)
            && str_contains($failUrl, 'order_id='.$order->id);
    });
});

it('обновляет статус платежа через API статуса', function (): void {
    Http::fake([
        '*/GetState' => Http::response(['Success' => true, 'ErrorCode' => '0', 'Status' => 'CONFIRMED'], 200),
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

it('отклоняет webhook с неверной подписью', function (): void {
    config(['services.tbank.password' => 'test-password']);

    $order = Order::factory()->create();
    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'external_order_id' => 'ext-order-bad-sign',
        'status' => Payment::STATUS_PENDING,
    ]);

    $payload = tbankNotification([
        'OrderId' => $payment->public_id,
        'PaymentId' => 'ext-order-bad-sign',
    ]);
    $payload['Token'] = 'invalid';

    $this->postJson('/api/v1/payments/tbank/webhook', $payload)->assertForbidden();

    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => Payment::STATUS_PENDING,
    ]);
});

it('возвращает остатки на склад при возврате платежа', function (): void {
    Http::fake([
        '*' => Http::response(['errorCode' => 0], 200),
    ]);

    $product = Product::factory()->withStock(3)->create();
    $order = Order::factory()->create();
    \App\Models\OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'count' => 2,
    ]);
    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'external_order_id' => 'ext-order-refund-stock',
        'status' => Payment::STATUS_PAID,
    ]);

    $response = $this->postJson("/api/v1/payments/{$payment->public_id}/refund", [
        'amount' => 100.00,
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'stock' => 5,
    ]);
    $this->assertNotNull($order->fresh()->stock_released_at);
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

    config(['services.tbank.password' => 'test-password']);

    $response = $this->postJson('/api/v1/payments/tbank/webhook', tbankNotification([
        'OrderId' => $payment->public_id,
        'PaymentId' => 'ext-order-4',
        'Status' => 'CONFIRMED',
    ]));

    $response->assertOk()
        ->assertSee('OK');

    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => Payment::STATUS_PAID,
    ]);
});

it('создает платеж по сумме со скидкой и с учетом доставки', function (): void {
    Http::fake([
        '*' => Http::response(['Success' => true, 'ErrorCode' => '0', 'PaymentId' => 'ext-order-discount', 'PaymentURL' => 'https://pay.test/form-discount'], 200),
    ]);

    $this->mock(AddressSuggestService::class, function ($mock): void {
        $mock->shouldReceive('isDeliverableAddress')->andReturn(true);
    });
    $this->mock(ShippingCalculationService::class, function ($mock): void {
        $mock->shouldReceive('hasShippableProducts')->andReturn(true);
        $mock->shouldReceive('calculatePriceForDeliveryType')->andReturn(100);
    });

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
        return str_contains($request->url(), '/Init')
            && $request['Amount'] === 90000;
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

it('передает Receipt с фискальными данными когда фискализация включена', function (): void {
    config([
        'services.tbank.fiscal.enabled' => true,
        'services.tbank.fiscal.taxation' => 'usn_income',
        'services.tbank.fiscal.tax' => 'vat5',
        'services.tbank.fiscal.payment_method' => 'full_payment',
        'services.tbank.fiscal.payment_object' => 'commodity',
        'services.tbank.fiscal.delivery_payment_object' => 'service',
        'services.tbank.fiscal.delivery_name' => 'Доставка',
    ]);

    Http::fake([
        '*' => Http::response(['Success' => true, 'ErrorCode' => '0', 'PaymentId' => 'ext-order-fiscal', 'PaymentURL' => 'https://pay.test/form-fiscal'], 200),
    ]);

    $product = Product::factory()->create(['price' => 500.00]);
    $order = Order::factory()->create([
        'email' => 'fiscal@example.com',
        'phone' => '+79991234567',
        'total_price' => 600,
        'total_price_with_discount' => 500,
        'delivery_price' => 100,
    ]);
    \App\Models\OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name' => 'Магический товар',
        'price' => 500.00,
        'count' => 1,
        'total' => 500.00,
    ]);

    $response = $this->postJson('/api/v1/payments', [
        'order_id' => $order->id,
    ]);

    $response->assertOk();

    Http::assertSent(function (\Illuminate\Http\Client\Request $request): bool {
        if (! str_contains($request->url(), '/Init')) {
            return false;
        }

        $receipt = $request['Receipt'] ?? null;
        if (! is_array($receipt)) {
            return false;
        }

        return ($receipt['Taxation'] ?? null) === 'usn_income'
            && ($receipt['Email'] ?? null) === 'fiscal@example.com'
            && ! empty($receipt['Items']);
    });
});

it('не передает Receipt когда фискализация отключена', function (): void {
    config(['services.tbank.fiscal.enabled' => false]);

    Http::fake([
        '*' => Http::response(['Success' => true, 'ErrorCode' => '0', 'PaymentId' => 'ext-order-nofiscal', 'PaymentURL' => 'https://pay.test/form-nofiscal'], 200),
    ]);

    $order = Order::factory()->create([
        'total_price' => 1000,
        'total_price_with_discount' => 1000,
        'delivery_price' => 0,
    ]);

    $response = $this->postJson('/api/v1/payments', [
        'order_id' => $order->id,
    ]);

    $response->assertOk();

    Http::assertSent(function (\Illuminate\Http\Client\Request $request): bool {
        if (! str_contains($request->url(), '/Init')) {
            return false;
        }

        return ! isset($request['Receipt']);
    });
});

it('включает доставку как позицию в Receipt', function (): void {
    config([
        'services.tbank.fiscal.enabled' => true,
        'services.tbank.fiscal.taxation' => 'usn_income',
        'services.tbank.fiscal.tax' => 'vat5',
        'services.tbank.fiscal.payment_method' => 'full_payment',
        'services.tbank.fiscal.payment_object' => 'commodity',
        'services.tbank.fiscal.delivery_payment_object' => 'service',
        'services.tbank.fiscal.delivery_name' => 'Доставка',
    ]);

    Http::fake([
        '*' => Http::response(['Success' => true, 'ErrorCode' => '0', 'PaymentId' => 'ext-order-delivery', 'PaymentURL' => 'https://pay.test/form-delivery'], 200),
    ]);

    $product = Product::factory()->create(['price' => 300.00]);
    $order = Order::factory()->create([
        'email' => 'delivery-fiscal@example.com',
        'total_price' => 400,
        'total_price_with_discount' => 300,
        'delivery_price' => 100,
    ]);
    \App\Models\OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'name' => 'Товар',
        'price' => 300.00,
        'count' => 1,
        'total' => 300.00,
    ]);

    $response = $this->postJson('/api/v1/payments', [
        'order_id' => $order->id,
    ]);

    $response->assertOk();

    Http::assertSent(function (\Illuminate\Http\Client\Request $request): bool {
        if (! str_contains($request->url(), '/Init')) {
            return false;
        }

        $items = $request['Receipt']['Items'] ?? null;
        if (! is_array($items) || count($items) !== 2) {
            return false;
        }

        $deliveryItem = $items[1];

        return $deliveryItem['Name'] === 'Доставка'
            && $deliveryItem['Price'] === 10000
            && $deliveryItem['PaymentObject'] === 'service';
    });
});

it('полный цикл: calculate → store → payment с промокодом и 3 товарами', function (): void {
    Http::fake([
        '*' => Http::response(['Success' => true, 'ErrorCode' => '0', 'PaymentId' => 'ext-order-e2e', 'PaymentURL' => 'https://pay.test/form-e2e'], 200),
    ]);

    $this->mock(AddressSuggestService::class, function ($mock): void {
        $mock->shouldReceive('isDeliverableAddress')->andReturn(true);
    });
    $this->mock(ShippingCalculationService::class, function ($mock): void {
        $mock->shouldReceive('hasShippableProducts')->andReturn(true);
        $mock->shouldReceive('calculatePriceForDeliveryType')->andReturn(250);
    });

    $category = Category::factory()->create();
    $product1 = Product::factory()->create(['price' => 500]);
    $product2 = Product::factory()->create(['price' => 300]);
    $product3 = Product::factory()->create(['price' => 200]);
    $product1->categories()->attach($category->id);
    $product2->categories()->attach($category->id);
    $product3->categories()->attach($category->id);

    $promo = PromoCode::factory()->create([
        'discount_type' => 'percent',
        'discount_value' => 15,
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
    $promo->categories()->attach($category->id);

    $items = [
        ['id' => $product1->id, 'count' => 1],
        ['id' => $product2->id, 'count' => 2],
        ['id' => $product3->id, 'count' => 1],
    ];

    $calcResponse = $this->postJson('/api/v1/order/calculate', [
        'items' => $items,
        'promo_code' => $promo->code,
    ]);
    $calcResponse->assertOk();
    $calcResponse->assertJson([
        'data' => [
            'total_without_discount' => 1300,
            'total_with_discount' => 1105,
            'promo_code_status' => 'applied',
        ],
    ]);

    $orderResponse = $this->postJson('/api/v1/order', [
        'items' => $items,
        'promo_code' => $promo->code,
        'register' => false,
        'delivery_type' => 'Cdek',
        'delivery_price' => 250,
        'first_name' => 'Тест',
        'last_name' => 'Тестов',
        'email' => 'e2e-promo@example.com',
        'address' => 'ул. Тестовая, 1',
    ]);
    $orderResponse->assertCreated();

    $orderId = (int) $orderResponse->json('data.id');

    $this->assertDatabaseHas('orders', [
        'id' => $orderId,
        'promo_code_id' => $promo->id,
        'total_price' => 1105,
        'total_price_without_discount' => 1300,
        'total_price_with_discount' => 1105,
        'delivery_price' => 250,
    ]);

    $this->assertDatabaseCount('order_items', 3);

    $paymentResponse = $this->postJson('/api/v1/payments', [
        'order_id' => $orderId,
    ]);
    $paymentResponse->assertOk();

    Http::assertSent(function (\Illuminate\Http\Client\Request $request): bool {
        return str_contains($request->url(), '/Init')
            && $request['Amount'] === 135500;
    });

    $this->assertDatabaseHas('payments', [
        'order_id' => $orderId,
        'amount' => 1355,
    ]);
});

it('берет для оплаты сумму со скидкой, а не без скидки', function (): void {
    Http::fake([
        '*' => Http::response(['Success' => true, 'ErrorCode' => '0', 'PaymentId' => 'ext-order-price-check', 'PaymentURL' => 'https://pay.test/form-check'], 200),
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
        return str_contains($request->url(), '/Init')
            && $request['Amount'] === 75000;
    });

    $this->assertDatabaseHas('payments', [
        'order_id' => $order->id,
        'amount' => 750,
        'external_order_id' => 'ext-order-price-check',
    ]);
});

it('не создаёт платёж по отменённому заказу со снятым складом', function (): void {
    $order = Order::factory()->create([
        'status' => 'cancelled',
        'stock_released_at' => now(),
    ]);

    $response = $this->postJson('/api/v1/payments', [
        'order_id' => $order->id,
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('message', 'Заказ отменён, оплата недоступна.');
});

it('повторный возврат не накручивает склад', function (): void {
    Http::fake([
        '*' => Http::response(['errorCode' => 0], 200),
    ]);

    $product = Product::factory()->withStock(3)->create();
    $order = Order::factory()->create(['status' => 'paid']);
    \App\Models\OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'count' => 2,
    ]);
    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'external_order_id' => 'ext-order-refund-twice',
        'status' => Payment::STATUS_PAID,
    ]);

    $this->postJson("/api/v1/payments/{$payment->public_id}/refund")->assertOk();
    $this->postJson("/api/v1/payments/{$payment->public_id}/refund")->assertOk();

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'stock' => 5,
    ]);
});

it('не переводит отменённый заказ в paid по webhook', function (): void {
    $order = Order::factory()->create([
        'status' => 'cancelled',
        'stock_released_at' => now(),
    ]);
    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'external_order_id' => 'ext-order-late-paid',
        'status' => Payment::STATUS_PENDING,
    ]);

    config(['services.tbank.password' => 'test-password']);

    $this->postJson('/api/v1/payments/tbank/webhook', tbankNotification([
        'OrderId' => $payment->public_id,
        'PaymentId' => 'ext-order-late-paid',
        'Status' => 'CONFIRMED',
    ]))->assertOk();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'cancelled',
    ]);
    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => Payment::STATUS_PAID,
    ]);
});
