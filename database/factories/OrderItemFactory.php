<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $count = $this->faker->numberBetween(1, 5);
        $price = $product->price;

        return [
            'order_id' => $order?->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $price,
            'count' => $count,
            'total' => $price * $count,
        ];
    }
}
