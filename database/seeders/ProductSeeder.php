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
    private const PRODUCTS_PER_CATEGORY = 5;

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

        foreach ($categories as $category) {
            // Создаем обычные продукты
            Product::factory(self::PRODUCTS_PER_CATEGORY)
                ->create()
                ->each(fn (Product $product) => $product->categories()->attach($category));

            // Создаем продукты со скидкой
            Product::factory(2)
                ->withDiscount()
                ->create()
                ->each(fn (Product $product) => $product->categories()->attach($category));

            // Создаем новинки
            Product::factory(2)
                ->markAsNew()
                ->create()
                ->each(fn (Product $product) => $product->categories()->attach($category));

            // Создаем хиты продаж
            Product::factory(1)
                ->bestseller()
                ->create()
                ->each(fn (Product $product) => $product->categories()->attach($category));
        }

        // Добавляем связанные продукты
        Product::all()->each(function (Product $product) {
            $relatedProducts = Product::where('id', '!=', $product->id)
                ->inRandomOrder()
                ->limit(3)
                ->get();

            $product->related()->attach($relatedProducts);
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
