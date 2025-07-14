<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PromoCode>
 */
class PromoCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = now();
        $end = now()->addDays(7);

        return [
            'code' => strtoupper($this->faker->bothify('PROMO####')), // Пример: PROMO1234
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'discount_type' => $this->faker->randomElement(['percent', 'fixed']),
            'discount_value' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
