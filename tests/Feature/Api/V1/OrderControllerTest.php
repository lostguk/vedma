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
            'summery' => 200,
            'summery_old' => 240,
            'discounted' => false,
            'wholesale' => false,
        ]);
        $response->assertJson([
            'data' => [
                'total_without_discount' => 200,
                'total_with_discount' => 200,
                'promo_code_status' => 'not_sent',
            ],
        ]);
    }

    public function test_calculate_applies_wholesale_to_every_unit_at_threshold(): void
    {
        $product = Product::factory()->create([
            'price' => 1000,
            'old_price' => 1200,
            'wholesale_min_qty' => 5,
            'wholesale_price' => 800,
        ]);

        $this->postJson('/api/v1/order/calculate', [
            'items' => [
                ['id' => $product->id, 'count' => 5],
            ],
        ])->assertOk()
            ->assertJsonFragment([
                'id' => $product->id,
                'count' => 5,
                'price' => 800,
                'price_without_discount' => 800,
                'summery' => 4000,
                'summery_without_discount' => 4000,
                'discounted' => false,
                'wholesale' => true,
            ])
            ->assertJsonPath('data.total_without_discount', 4000)
            ->assertJsonPath('data.total_with_discount', 4000);
    }

    public function test_calculate_keeps_retail_price_below_wholesale_threshold(): void
    {
        $product = Product::factory()->create([
            'price' => 1000,
            'wholesale_min_qty' => 5,
            'wholesale_price' => 800,
        ]);

        $this->postJson('/api/v1/order/calculate', [
            'items' => [
                ['id' => $product->id, 'count' => 4],
            ],
        ])->assertOk()
            ->assertJsonFragment([
                'price' => 1000,
                'summery' => 4000,
                'wholesale' => false,
            ]);
    }

    public function test_calculate_ignores_promo_when_wholesale_is_active(): void
    {
        $category = \App\Models\Category::factory()->create();
        $product = Product::factory()->create([
            'price' => 1000,
            'wholesale_min_qty' => 2,
            'wholesale_price' => 800,
        ]);
        $product->categories()->attach($category->id);
        $promo = PromoCode::factory()->create([
            'discount_type' => 'percent',
            'discount_value' => 50,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        $promo->categories()->attach($category->id);

        $this->postJson('/api/v1/order/calculate', [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
            'promo_code' => $promo->code,
        ])->assertOk()
            ->assertJsonFragment([
                'price' => 800,
                'price_without_discount' => 800,
                'summery' => 1600,
                'discounted' => false,
                'wholesale' => true,
            ])
            ->assertJsonPath('data.promo_code_status', 'not_applied')
            ->assertJsonPath('data.total_with_discount', 1600);
    }

    public function test_calculate_applies_promo_only_to_retail_lines(): void
    {
        $category = \App\Models\Category::factory()->create();
        $wholesaleProduct = Product::factory()->create([
            'price' => 1000,
            'wholesale_min_qty' => 5,
            'wholesale_price' => 800,
        ]);
        $retailProduct = Product::factory()->create(['price' => 200]);
        $wholesaleProduct->categories()->attach($category->id);
        $retailProduct->categories()->attach($category->id);
        $promo = PromoCode::factory()->create([
            'discount_type' => 'percent',
            'discount_value' => 10,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        $promo->categories()->attach($category->id);

        $response = $this->postJson('/api/v1/order/calculate', [
            'items' => [
                ['id' => $wholesaleProduct->id, 'count' => 5],
                ['id' => $retailProduct->id, 'count' => 1],
            ],
            'promo_code' => $promo->code,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.promo_code_status', 'applied')
            ->assertJsonPath('data.total_without_discount', 4200)
            ->assertJsonPath('data.total_with_discount', 4180);

        $items = collect($response->json('data.items'))->keyBy('id');
        $this->assertTrue($items[$wholesaleProduct->id]['wholesale']);
        $this->assertFalse($items[$wholesaleProduct->id]['discounted']);
        $this->assertSame(4000, $items[$wholesaleProduct->id]['summery']);
        $this->assertFalse($items[$retailProduct->id]['wholesale']);
        $this->assertTrue($items[$retailProduct->id]['discounted']);
        $this->assertSame(180, $items[$retailProduct->id]['summery']);
    }

    public function test_calculate_skips_wholesale_when_stock_cannot_reach_it(): void
    {
        $product = Product::factory()->withStock(3)->create([
            'price' => 1000,
            'wholesale_min_qty' => 5,
            'wholesale_price' => 800,
        ]);

        $this->postJson('/api/v1/order/calculate', [
            'items' => [
                ['id' => $product->id, 'count' => 3],
            ],
        ])->assertOk()
            ->assertJsonFragment([
                'price' => 1000,
                'summery' => 3000,
                'wholesale' => false,
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
                'total_without_discount' => 100,
                'total_with_discount' => 100,
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
            'price_without_discount' => 200,
            'summery' => 540, // 200*0.9*3
            'summery_without_discount' => 600,
            'discounted' => true,
        ]);
        $response->assertJson([
            'data' => [
                'total_without_discount' => 600, // 200*3
                'total_with_discount' => 540, // 200*0.9*3
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
            'summery' => 200,
            'discounted' => false,
        ]);
        $response->assertJson([
            'data' => [
                'total_without_discount' => 200,
                'total_with_discount' => 200,
                'promo_code_status' => 'not_applied',
            ],
        ]);
    }

    public function test_user_can_see_only_their_orders(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $authUser */
        $authUser = $user;
        $otherUser = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);
        Order::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $this->actingAs($authUser);
        $response = $this->getJson('/api/v1/orders');

        $response->assertOk();
        $ordersArray = $response->json('data.data');
        $this->assertIsArray($ordersArray);
        $this->assertCount(3, $ordersArray);
        foreach ($ordersArray as $order) {
            $this->assertEquals($user->id, $order['user_id']);
        }
    }

    public function test_orders_list_returns_human_status_and_totals(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $authUser */
        $authUser = $user;
        $category = \App\Models\Category::factory()->create();
        $product = Product::factory()->create(['price' => 100, 'old_price' => 120]);
        $product->categories()->attach($category->id);
        $promo = PromoCode::factory()->create([
            'discount_type' => 'percent',
            'discount_value' => 10,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        $promo->categories()->attach($category->id);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'promo_code_id' => $promo->id,
            'status' => 'paid',
            'phone' => '+7 999 000 00 00',
            'total_price' => 180,
            'total_price_without_discount' => 200,
            'total_price_with_discount' => 180,
            'delivery_price' => 350,
        ]);
        \App\Models\OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => 90,
            'price_without_discount' => 100,
            'count' => 2,
            'total' => 180,
        ]);

        $this->actingAs($authUser);
        $response = $this->getJson('/api/v1/orders');

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $order->id,
            'phone' => '+7 999 000 00 00',
            'promo_code' => $promo->code,
            'status' => 'Оплачен',
            'status_code' => 'paid',
            'total_without_discount' => 200,
            'total_with_discount' => 180,
            'discount_amount' => 20,
            'payable_total' => 530,
            'promo_code_status' => 'applied',
        ]);
        $response->assertJsonFragment([
            'product_id' => $product->id,
            'price' => 90,
            'price_without_discount' => 100,
            'count' => 2,
            'total' => 180,
            'total_without_discount' => 200,
        ]);
    }
}
