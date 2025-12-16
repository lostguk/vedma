<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Order;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_without_promo_code(): void
    {
        $product = Product::factory()->create(['price' => 100, 'old_price' => 120]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
        ];
        $response = $this->postJson(route('api.v1.order.calculate', $payload));
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => ['id', 'name', 'count', 'summery', 'summery_old', 'discounted'],
                ],
                'total_without_discount',
                'total_with_discount',
                'promo_code_status',
            ],
        ]);
        $response->assertJsonFragment([
            'id' => $product->id,
            'count' => 2,
            'summery' => 200.0,
            'summery_old' => 240.0,
            'discounted' => false,
        ]);
        $response->assertJson([
            'data' => [
                'total_without_discount' => 200.0,
                'total_with_discount' => 200.0,
                'promo_code_status' => 'not_exists',
            ],
        ]);
    }

    public function test_calculate_with_invalid_promo_code(): void
    {
        $product = Product::factory()->create(['price' => 100]);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 1],
            ],
            'promo_code' => 'INVALID',
        ];
        $response = $this->postJson('/api/v1/order/calculate', $payload);
        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $product->id,
            'discounted' => false,
        ]);
        $response->assertJson([
            'data' => [
                'total_without_discount' => 100.0,
                'total_with_discount' => 100.0,
                'promo_code_status' => 'not_exists',
            ],
        ]);
    }

    public function test_calculate_with_percent_promo_code(): void
    {
        $category = \App\Models\Category::factory()->create();
        $product = Product::factory()->create(['price' => 200]);
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
                ['id' => $product->id, 'count' => 3],
            ],
            'promo_code' => $promo->code,
        ];
        $response = $this->postJson('/api/v1/order/calculate', $payload);
        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $product->id,
            'count' => 3,
            'summery' => 540.0, // 200*0.9*3
            'discounted' => true,
        ]);
        $response->assertJson([
            'data' => [
                'total_without_discount' => 600.0, // 200*3
                'total_with_discount' => 540.0, // 200*0.9*3
                'promo_code_status' => 'applied',
            ],
        ]);
    }

    public function test_calculate_with_fixed_promo_code(): void
    {
        $category = \App\Models\Category::factory()->create();
        $product = Product::factory()->create(['price' => 150]);
        $product->categories()->attach($category->id);
        $promo = PromoCode::factory()->create([
            'discount_type' => 'fixed',
            'discount_value' => 20,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        $promo->categories()->attach($category->id);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
            'promo_code' => $promo->code,
        ];
        $response = $this->postJson('/api/v1/order/calculate', $payload);
        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $product->id,
            'count' => 2,
            'summery' => 260.0, // (150-20)*2
            'discounted' => true,
        ]);
        $response->assertJson([
            'data' => [
                'total_without_discount' => 300.0, // 150*2
                'total_with_discount' => 260.0, // (150-20)*2
                'promo_code_status' => 'applied',
            ],
        ]);
    }

    public function test_calculate_with_promo_code_not_applied(): void
    {
        $category1 = \App\Models\Category::factory()->create();
        $category2 = \App\Models\Category::factory()->create();
        $product = Product::factory()->create(['price' => 100]);
        // Товар в категории 1, промокод для категории 2
        $product->categories()->attach($category1->id);
        $promo = PromoCode::factory()->create([
            'discount_type' => 'percent',
            'discount_value' => 10,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        $promo->categories()->attach($category2->id);
        $payload = [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
            'promo_code' => $promo->code,
        ];
        $response = $this->postJson('/api/v1/order/calculate', $payload);
        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $product->id,
            'count' => 2,
            'summery' => 200.0,
            'discounted' => false,
        ]);
        $response->assertJson([
            'data' => [
                'total_without_discount' => 200.0,
                'total_with_discount' => 200.0,
                'promo_code_status' => 'not_applied',
            ],
        ]);
    }

    public function test_user_can_see_only_their_orders(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);
        Order::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $this->actingAs($user);
        $response = $this->getJson('/api/v1/orders');

        $response->assertOk();
        $ordersArray = $response->json('data.data');
        $this->assertIsArray($ordersArray);
        $this->assertCount(3, $ordersArray);
        foreach ($ordersArray as $order) {
            $this->assertEquals($user->id, $order['user_id']);
        }
    }
}
