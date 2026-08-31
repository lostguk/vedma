<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PromoBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PromoBanner>
 */
final class PromoBannerFactory extends Factory
{
    protected $model = PromoBanner::class;

    public function definition(): array
    {
        return [
            'is_active' => false,
            'kicker' => 'Особое предложение',
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(12),
            'discount_value' => '−20%',
            'discount_caption' => 'на ритуальные свечи',
            'promo_code' => 'VEDMA20',
            'button_text' => 'Смотреть каталог',
            'button_url' => '/catalog',
            'starts_at' => null,
            'ends_at' => null,
        ];
    }

    public function active(): self
    {
        return $this->state(fn (): array => ['is_active' => true]);
    }
}
