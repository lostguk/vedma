<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->randomFloat(2, 100, 10000),
            'old_price' => fake()->boolean(20) ? fake()->randomFloat(2, 100, 10000) : null,
            'weight' => fake()->randomFloat(3, 100, 5000),
            'width' => fake()->randomFloat(2, 1, 100),
            'height' => fake()->randomFloat(2, 1, 100),
            'length' => fake()->randomFloat(2, 1, 100),
            'is_new' => false,
            'is_bestseller' => false,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Пометить продукт как новинку.
     */
    public function markAsNew(): static
    {
        return $this->state(['is_new' => true]);
    }

    /**
     * Пометить продукт как хит продаж.
     */
    public function bestseller(): static
    {
        return $this->state(['is_bestseller' => true]);
    }

    /**
     * Установить старую цену выше текущей.
     */
    public function withDiscount(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'old_price' => $attributes['price'] * 1.2,
            ];
        });
    }

    /**
     * Добавить все размеры продукта.
     */
    public function withDimensions(): static
    {
        return $this->state([
            'width' => fake()->randomFloat(2, 1, 100),
            'height' => fake()->randomFloat(2, 1, 100),
            'length' => fake()->randomFloat(2, 1, 100),
        ]);
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            // Создаем тестовое изображение
            $path = storage_path('app/public/test-images/product.jpg');

            // Если файл не существует, создаем его
            if (! file_exists($path)) {
                if (! file_exists(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }
                // Создаем простое изображение
                $image = imagecreatetruecolor(800, 600);
                $bgColor = imagecolorallocate($image, 200, 200, 200);
                imagefill($image, 0, 0, $bgColor);
                $textColor = imagecolorallocate($image, 0, 0, 0);
                imagestring($image, 5, 350, 280, "Product {$product->id}", $textColor);
                imagejpeg($image, $path);
                imagedestroy($image);
            }

            // Добавляем изображение к продукту
            $product
                ->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection(Product::IMAGES_COLLECTION);
        });
    }
}
