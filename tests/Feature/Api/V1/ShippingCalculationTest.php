<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShippingCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipping_calculate_endpoint_available(): void
    {
        $product = Product::factory()->create();
        Http::fake(['*/offers' => Http::response(['price' => 0, 'options' => []], 200)]);

        $response = $this->postJson('/api/v1/shipping/calculate', [
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
            'address' => 'Москва, ул. Пушкина, д. 1',
        ]);
        $response->assertOk();
        $response->assertJsonFragment([
            'status' => 'success',
        ]);
    }

    public function test_validation_fails_without_products(): void
    {
        $response = $this->postJson('/api/v1/shipping/calculate', [
            'address' => 'Москва, ул. Пушкина, д. 1',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products']);
    }

    public function test_validation_fails_without_address(): void
    {
        $product = Product::factory()->create();
        $response = $this->postJson('/api/v1/shipping/calculate', [
            'products' => [
                ['id' => $product->id, 'quantity' => 1],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['address']);
    }

    public function test_validation_fails_with_invalid_product_id(): void
    {
        $response = $this->postJson('/api/v1/shipping/calculate', [
            'products' => [
                ['id' => 999999, 'quantity' => 1],
            ],
            'address' => 'Москва, ул. Пушкина, д. 1',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_validation_fails_with_invalid_quantity(): void
    {
        $product = Product::factory()->create();
        $response = $this->postJson('/api/v1/shipping/calculate', [
            'products' => [
                ['id' => $product->id, 'quantity' => 0],
            ],
            'address' => 'Москва, ул. Пушкина, д. 1',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_calculate_uses_metaship_and_returns_response(): void
    {
        Config::set('services.metaship.api_key', 'test-key');
        Config::set('services.metaship.api_secret', 'test-secret');

        $product = Product::factory()->create([
            'weight' => 700,
            'width' => 10,
            'height' => 11,
            'length' => 12,
        ]);

        Http::fake([
            'https://api.metaship.ru/auth/access_token' => Http::response(['access_token' => 'test-token'], 200),
            'https://api.metaship.ru/v2/offers*' => function ($request) {
                // Проверяем заголовок авторизации
                $this->assertSame('Bearer test-token', $request->header('Authorization')[0] ?? null);

                // Проверяем полезную нагрузку
                $data = $request->data();
                $this->assertSame('Москва, ул. Пушкина, д. 1', $data['address'] ?? null);
                $this->assertSame(10, $data['width'] ?? null);
                $this->assertSame(11, $data['height'] ?? null);
                $this->assertSame(12, $data['length'] ?? null);
                $this->assertSame(1.4, (float) ($data['weight'] ?? null));

                return Http::response([
                    'price' => 350,
                    'options' => [
                        ['carrier' => 'CDEK', 'service' => 'Курьер', 'price' => 350],
                    ],
                ], 200);
            },
        ]);

        $response = $this->postJson('/api/v1/shipping/calculate', [
            'products' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
            'address' => 'Москва, ул. Пушкина, д. 1',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.price', 350);
        $response->assertJsonPath('data.options.0.carrier', 'CDEK');
    }

    public function test_calculate_converts_product_weight_from_grams_to_kilograms(): void
    {
        Config::set('services.metaship.api_key', 'test-key');
        Config::set('services.metaship.api_secret', 'test-secret');

        $product = Product::factory()->create([
            'weight' => 60,
            'width' => 20,
            'height' => 18,
            'length' => 16,
        ]);

        Http::fake([
            'https://api.metaship.ru/auth/access_token' => Http::response(['access_token' => 'test-token'], 200),
            'https://api.metaship.ru/v2/offers*' => function ($request) {
                $data = $request->data();
                $this->assertSame(0.1, (float) ($data['weight'] ?? null));

                return Http::response([], 200);
            },
        ]);

        $this->postJson('/api/v1/shipping/calculate', [
            'products' => [
                ['id' => $product->id, 'quantity' => 1],
            ],
            'address' => 'Москва, ул. Пушкина, д. 1',
        ])->assertOk();
    }
}
