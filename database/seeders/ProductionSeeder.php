<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class ProductionSeeder extends Seeder
{
    /**
     * Идемпотентные сидеры для первичной инициализации production.
     * Без товаров, заказов, промокодов и демо-данных.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            PageSeeder::class,
            HomePageContentSeeder::class,
            HeroSlideSeeder::class,
        ]);
    }
}
