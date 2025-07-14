<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $products = Product::all();
        // Для каждой категории создаём промокод и связываем с товарами этой категории
        foreach ($categories as $category) {
            $promo = PromoCode::factory()->create();
            $promo->categories()->attach($category->id);
            // Для тестов: выводим в консоль id промокода и id товаров
            $categoryProducts = $products->filter(fn ($p) => $p->categories->contains($category->id));
            if ($categoryProducts->isNotEmpty()) {
                // Просто для наглядности, можно добавить логику если нужно
                // info("PromoCode {$promo->code} -> Category {$category->id} -> Products: " . $categoryProducts->pluck('id')->join(','));
            }
        }
        // Также создаём несколько универсальных промокодов для случайных категорий
        PromoCode::factory(3)->create()->each(function ($promoCode) use ($categories) {
            $promoCode->categories()->attach($categories->random(rand(2, 4))->pluck('id')->toArray());
        });
    }
}
