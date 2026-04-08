<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\HeroSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HeroSlide>
 */
final class HeroSlideFactory extends Factory
{
    protected $model = HeroSlide::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'accent' => fake()->words(2, true),
            'subtitle' => fake()->sentence(10),
            'button_text' => 'Смотреть',
            'button_url' => '/catalog',
            'image_path' => null,

            'is_active' => true,
        ];
    }
}
