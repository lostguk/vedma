<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PromoCode>
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
        ];
    }
}
