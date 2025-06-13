<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Список сидеров для запуска
     */
    protected array $seeders = [
        UserSeeder::class,
        TopicSeeder::class,
        MessageSeeder::class,
        CategorySeeder::class,
        ProductSeeder::class,
        PromoCodeSeeder::class,
        PageSeeder::class,
        OrderSeeder::class,
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
