<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'parent_id' => null,
            'sort_order' => fake()->numberBetween(1, 100),
            'is_visible' => true,
            'meta_title' => fake()->words(3, true),
            'meta_description' => fake()->sentence(),
        ];
    }

    /**
     * Указывает, что категория является дочерней
     */
    public function child(?Category $parent = null): static
    {
        return $this->state(function (array $attributes) use ($parent) {
            return [
                'parent_id' => $parent?->id,
            ];
        });
    }
}
