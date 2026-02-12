<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $promo = PromoCode::inRandomOrder()->first();
        $userData = $user ? $user->toArray() : UserFactory::new()->definition();
        $totalWithoutDiscount = $this->faker->numberBetween(1000, 10000);
        $discountPercent = $this->faker->numberBetween(0, 30);
        $totalWithDiscount = (int) round($totalWithoutDiscount * (1 - ($discountPercent / 100)));

        return [
            'user_id' => $user?->id,
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'middle_name' => $userData['middle_name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'address' => $userData['address'],
            'promo_code_id' => $promo?->id,
            'total_price' => $totalWithDiscount,
            'total_price_without_discount' => $totalWithoutDiscount,
            'total_price_with_discount' => $totalWithDiscount,
            'status' => $this->faker->randomElement(['new', 'processing', 'paid', 'cancelled']),
            'payment_type' => $this->faker->randomElement(['card', 'cash', 'online']),
            'paid_at' => null,
            'comment' => $this->faker->optional()->sentence(),
            'delivery_type' => $this->faker->randomElement(['PostOffice', 'Cdek']),
            'delivery_price' => $this->faker->numberBetween(0, 500),
            'delivery_status' => $this->faker->randomElement(['pending', 'shipped', 'delivered']),
            'delivery_data' => null,
        ];
    }
}
