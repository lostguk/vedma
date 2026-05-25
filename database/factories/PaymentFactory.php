<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        return [
            'order_id' => $order->id,
            'provider' => 'alfabank',
            'status' => $this->faker->randomElement([
                Payment::STATUS_CREATED,
                Payment::STATUS_REGISTERED,
                Payment::STATUS_PENDING,
                Payment::STATUS_PAID,
                Payment::STATUS_FAILED,
            ]),
            'amount' => $order->total_price + ($order->delivery_price ?? 0),
            'currency' => 'RUB',
            'external_order_id' => $this->faker->uuid(),
            'payment_url' => $this->faker->url(),
            'payload' => null,
            'error_message' => null,
            'paid_at' => null,
            'refunded_at' => null,
        ];
    }
}
