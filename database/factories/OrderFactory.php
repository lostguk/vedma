<?php

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

        return [
            'user_id' => $user?->id,
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'middle_name' => $userData['middle_name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'address' => $userData['address'],
            'promo_code_id' => $promo?->id,
            'total_price' => $this->faker->randomFloat(2, 1000, 10000),
            'status' => $this->faker->randomElement(['new', 'processing', 'paid', 'cancelled']),
            'payment_type' => $this->faker->randomElement(['card', 'cash', 'online']),
            'paid_at' => null,
            'comment' => $this->faker->optional()->sentence(),
            'delivery_type' => $this->faker->randomElement(['pickup', 'courier', 'post']),
            'delivery_price' => $this->faker->randomFloat(2, 0, 500),
            'delivery_status' => $this->faker->randomElement(['pending', 'shipped', 'delivered']),
            'delivery_data' => null,
        ];
    }
}
