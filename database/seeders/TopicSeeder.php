<?php

namespace Database\Seeders;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        // Create 3 topics for each user
        foreach ($users as $user) {
            Topic::factory()
                ->count(3)
                ->create([
                    'user_id' => $user->id,
                ]);
        }
    }
}
