<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем 10 заказов
        Order::factory(10)->create()->each(function (Order $order) {
            // Для каждого заказа создаем 1-4 позиции заказа
            $products = Product::inRandomOrder()->limit(rand(1, 4))->get();
            foreach ($products as $product) {
                $count = rand(1, 5);
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'count' => $count,
                    'total' => $product->price * $count,
                ]);
            }
        });
    }
}
