<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Количество продуктов для каждой категории
     */
    private const int PRODUCTS_PER_CATEGORY = 5;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! $this->shouldSeedTestData()) {
            return;
        }

        // Получаем все категории
        $categories = Category::all();
        $categoryCount = $categories->count();
        $products = collect();

        // Для каждой категории создаём товары и привязываем к ней
        foreach ($categories as $i => $category) {
            $created = Product::factory(self::PRODUCTS_PER_CATEGORY)
                ->create()
                ->each(function (Product $product) use ($category) {
                    $product->categories()->attach($category);
                });
            $products = $products->merge($created);
        }

        // Для каждого товара добавляем ещё 1-2 случайные категории (для тестов промокодов)
        foreach ($products as $product) {
            $extraCategories = $categories->whereNotIn('id', $product->categories->pluck('id'))->random(rand(0, min(2, $categoryCount - 1)))->pluck('id')->all();
            if (! empty($extraCategories)) {
                $product->categories()->attach($extraCategories);
            }
        }

        // Добавляем связанные продукты
        Product::all()->each(function (Product $product) {
            $relatedProducts = Product::where('id', '!=', $product->id)
                ->inRandomOrder()
                ->limit(3)
                ->get()
                ->pluck('id')
                ->toArray();
            $product->related()->syncWithoutDetaching($relatedProducts);
        });
    }

    /**
     * Определяет, нужно ли создавать тестовые данные
     */
    private function shouldSeedTestData(): bool
    {
        return app()->environment('local', 'development');
    }
}
