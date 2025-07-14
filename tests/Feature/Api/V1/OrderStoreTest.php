<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_оформляет_заказ_без_промокода_и_без_регистрации(): void
    {
        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test1@example.com',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test1@example.com',
            'total_price' => 200.0,
        ]);
    }

    public function test_оформляет_заказ_с_валидным_промокодом(): void
    {
        $product = Product::factory()->create(['price' => 100]);
        $promo = PromoCode::factory()->create([
            'discount_type' => 'percent',
            'discount_value' => 10,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        $promo->categories()->attach($product->categories()->pluck('id'));
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
            'promo_code' => $promo->code,
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test2@example.com',
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test2@example.com',
            'promo_code_id' => $promo->id,
        ]);
    }

    public function test_оформляет_заказ_с_невалидным_промокодом(): void
    {
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
        ];
        $response = $this->postJson('/api/v1/order', $payload);
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'email' => 'test3@example.com',
            'promo_code_id' => null,
        ]);
    }

    public function test_оформляет_заказ_с_регистрацией_пользователя(): void
    {
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
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'register' => false,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];
        $this->actingAs($user);
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
}
