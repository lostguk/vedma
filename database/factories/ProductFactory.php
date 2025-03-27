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
            'old_price' => fake()->optional(0.3)->randomFloat(2, 100, 10000),
            'weight' => fake()->randomFloat(3, 50, 5000),
            'width' => fake()->optional()->randomFloat(2, 1, 100),
            'height' => fake()->optional()->randomFloat(2, 1, 100),
            'length' => fake()->optional()->randomFloat(2, 1, 100),
            'is_new' => fake()->boolean(20),
            'is_bestseller' => fake()->boolean(10),
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
}
