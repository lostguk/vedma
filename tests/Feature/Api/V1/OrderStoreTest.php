<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use App\Services\DaData\AddressSuggestService;
use App\Services\Shipping\ShippingCalculationService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Mockery\MockInterface;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    private function mockShippingService(?int $price = 350): void
    {
        $this->mock(AddressSuggestService::class, function (MockInterface $mock): void {
            $mock->shouldReceive('isDeliverableAddress')
                ->andReturn(true);
        });
        $this->mock(ShippingCalculationService::class, function (MockInterface $mock) use ($price): void {
            $mock->shouldReceive('hasShippableProducts')
                ->andReturn(true);
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
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'price' => 90,
            'price_without_discount' => 100,
            'count' => 2,
            'total' => 180,
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
        Notification::fake();
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
        $user = User::where('email', 'test4@example.com')->firstOrFail();
        $this->assertDatabaseHas('orders', [
            'email' => 'test4@example.com',
            'user_id' => $user->id,
        ]);
        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, VerifyEmailNotification::class);
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

    public function test_не_оформляет_заказ_с_неполным_адресом(): void
    {
        $this->mock(AddressSuggestService::class, function (MockInterface $mock): void {
            $mock->shouldReceive('isDeliverableAddress')->andReturn(false);
        });
        $this->mock(ShippingCalculationService::class, function (MockInterface $mock): void {
            $mock->shouldReceive('hasShippableProducts')->andReturn(true);
            $mock->shouldReceive('calculatePriceForDeliveryType')->never();
        });

        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test_incomplete_address@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Москва',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['address']);
        $this->assertDatabaseMissing('orders', [
            'email' => 'test_incomplete_address@example.com',
        ]);
    }

    public function test_не_оформляет_заказ_если_metaship_не_рассчитал_доставку(): void
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
            'address' => 'Москва',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['address']);
        $this->assertDatabaseMissing('orders', [
            'email' => 'test_null_delivery@example.com',
        ]);
    }

    public function test_заказ_без_физических_товаров_создаётся_без_доставки(): void
    {
        $this->mock(ShippingCalculationService::class, function (MockInterface $mock): void {
            $mock->shouldReceive('hasShippableProducts')->andReturn(false);
            $mock->shouldReceive('calculatePriceForDeliveryType')->never();
        });

        $product = Product::factory()->create(['price' => 1500]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test_service_only@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Москва',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test_service_only@example.com',
            'delivery_price' => null,
        ]);
    }

    public function test_уменьшает_остаток_на_складе_при_оформлении_заказа(): void
    {
        $this->mockShippingService();

        $product = Product::factory()->withStock(10)->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 3],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test_stock_decrement@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];

        $response = $this->postJson('/api/v1/order', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 7,
        ]);
    }

    public function test_не_уменьшает_остаток_для_товара_с_безлимитным_складом(): void
    {
        $this->mockShippingService();

        $product = Product::factory()->unlimitedStock()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 5],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test_unlimited_stock@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];

        $response = $this->postJson('/api/v1/order', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => null,
        ]);
    }

    public function test_не_оформляет_заказ_при_недостаточном_остатке(): void
    {
        $this->mockShippingService();

        $product = Product::factory()->withStock(2)->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 5],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test_insufficient_stock@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];

        $response = $this->postJson('/api/v1/order', $payload);

        $response->assertUnprocessable()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Недостаточно товара «'.$product->name.'» на складе. Доступно: 2 шт.');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 2,
        ]);
        $this->assertDatabaseMissing('orders', [
            'email' => 'test_insufficient_stock@example.com',
        ]);
    }

    public function test_не_оформляет_заказ_если_товар_отсутствует_на_складе(): void
    {
        $this->mockShippingService();

        $product = Product::factory()->withStock(0)->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test_out_of_stock@example.com',
            'delivery_type' => 'PostOffice',
            'address' => 'Some Address',
        ];

        $response = $this->postJson('/api/v1/order', $payload);

        $response->assertUnprocessable()
            ->assertJsonPath('message', 'Недостаточно товара «'.$product->name.'» на складе. Доступно: 0 шт.');
        $this->assertDatabaseMissing('orders', [
            'email' => 'test_out_of_stock@example.com',
        ]);
    }
}
