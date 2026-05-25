<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use App\Services\Shipping\ShippingCalculationService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    private function mockShippingService(?int $price = 350): void
    {
        $this->mock(ShippingCalculationService::class, function (MockInterface $mock) use ($price): void {
            $mock->shouldReceive('calculatePriceForDeliveryType')
                ->andReturn($price);
        });
    }

    public function test_оформляет_заказ_без_промокода_и_без_регистрации(): void
    {
        $this->mockShippingService(300);

        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test1@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test1@example.com',
            'total_price' => 200,
            'total_price_without_discount' => 200,
            'total_price_with_discount' => 200,
            'delivery_price' => 300,
        ]);
    }

    public function test_оформляет_заказ_с_валидным_промокодом(): void
    {
        $this->mockShippingService();

        $category = Category::factory()->create();
        $product = Product::factory()->create(['price' => 100]);
        $product->categories()->attach($category->id);
        $promo = PromoCode::factory()->create([
            'discount_type' => 'percent',
            'discount_value' => 10,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        $promo->categories()->attach($category->id);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
            'promo_code' => $promo->code,
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test2@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test2@example.com',
            'promo_code_id' => $promo->id,
            'total_price_without_discount' => 200,
            'total_price_with_discount' => 180,
            'total_price' => 180,
        ]);
    }

    public function test_оформляет_заказ_с_невалидным_промокодом(): void
    {
        $this->mockShippingService();

        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'promo_code' => 'INVALIDCODE',
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test3@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test3@example.com',
            'promo_code_id' => null,
            'total_price_without_discount' => 100,
            'total_price_with_discount' => 100,
        ]);
    }

    public function test_оформляет_заказ_с_регистрацией_пользователя(): void
    {
        $this->mockShippingService();

        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'register' => true,
            'first_name' => 'Петр',
            'last_name' => 'Петров',
            'email' => 'test4@example.com',
            'password' => 'password123',
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('users', [
            'email' => 'test4@example.com',
        ]);
        $this->assertDatabaseHas('orders', [
            'email' => 'test4@example.com',
            'user_id' => User::where('email', 'test4@example.com')->first()->id,
        ]);
    }

    public function test_оформляет_заказ_авторизованным_пользователем(): void
    {
        $this->mockShippingService();

        $user = User::factory()->create();
        /** @var Authenticatable $authUser */
        $authUser = $user;
        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'register' => false,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];
        $this->actingAs($authUser);
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => $user->email,
            'user_id' => $user->id,
        ]);
    }

    public function test_не_оформляет_заказ_без_товаров(): void
    {
        $payload = [
            'items' => [],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test5@example.com',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertStatus(422);
    }

    public function test_delivery_price_игнорируется_из_запроса(): void
    {
        $this->mockShippingService(500);

        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'register' => false,
            'delivery_price' => 0,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test_ignore_price@example.com',
            'delivery_type' => 'Cdek',
            'address' => 'Москва, ул. Пушкина, д. 1',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test_ignore_price@example.com',
            'delivery_price' => 500,
        ]);
    }

    public function test_заказ_создаётся_с_null_delivery_price_если_metaship_недоступен(): void
    {
        $this->mockShippingService(null);

        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test_null_delivery@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Москва, ул. Пушкина, д. 1',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test_null_delivery@example.com',
            'delivery_price' => null,
        ]);
    }
}
