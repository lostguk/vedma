<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(3),
            'user_id' => User::factory(),
            'topic_id' => Topic::factory(),
        ];
    }
}
