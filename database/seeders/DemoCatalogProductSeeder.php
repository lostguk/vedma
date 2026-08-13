<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

final class DemoCatalogProductSeeder extends Seeder
{
    public const COUNT = 200;

    public const SLUG_PREFIX = 'demo-bulk-';

    /**
     * Наполняет каталог 200 товарами без картинок для проверки пагинации.
     * Повторный запуск заменяет предыдущие демо-товары, живые товары не трогает.
     */
    public function run(): void
    {
        Product::query()
            ->where('slug', 'like', self::SLUG_PREFIX.'%')
            ->get()
            ->each(function (Product $product): void {
                $product->categories()->detach();
                $product->related()->detach();
                $product->relatedToProducts()->detach();
                $product->delete();
            });

        $categoryIds = Category::query()
            ->where('is_visible', true)
            ->pluck('id');

        $types = ['Свеча', 'Ритуальная свеча', 'Тонкая свеча', 'Набор свечей', 'Восковая свеча'];
        $colors = ['красная', 'чёрная', 'белая', 'зелёная', 'лавандовая', 'золотая', 'синяя', 'розовая', 'янтарная', 'серебряная'];

        for ($i = 1; $i <= self::COUNT; $i++) {
            $price = random_int(150, 8000);
            $hasDiscount = random_int(1, 100) <= 20;
            $stockRoll = random_int(1, 100);

            $product = Product::query()->create([
                'name' => sprintf('%s %s №%d', $types[array_rand($types)], $colors[array_rand($colors)], $i),
                'description' => 'Демо-товар для проверки каталога и пагинации. Без изображений.',
                'price' => $price,
                'old_price' => $hasDiscount ? (int) round($price * 1.25) : null,
                'weight' => random_int(40, 800),
                'width' => random_int(2, 20),
                'height' => random_int(10, 30),
                'length' => random_int(2, 20),
                'is_new' => random_int(1, 100) <= 15,
                'is_bestseller' => random_int(1, 100) <= 10,
                'stock' => match (true) {
                    $stockRoll <= 15 => 0,
                    $stockRoll <= 20 => null,
                    default => random_int(1, 40),
                },
            ]);

            $product->slug = self::SLUG_PREFIX.$i;
            $product->saveQuietly();

            if ($categoryIds->isNotEmpty()) {
                $attachCount = min(2, $categoryIds->count());
                $product->categories()->attach(
                    $categoryIds->shuffle()->take(random_int(1, $attachCount))->all()
                );
            }
        }
    }
}
