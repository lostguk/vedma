<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\HomePageContent;
use Illuminate\Database\Seeder;

final class HomePageContentSeeder extends Seeder
{
    public function run(): void
    {
        // Единственная запись с id = 1
        HomePageContent::query()->updateOrCreate(
            ['id' => 1],
            HomePageContent::factory()->make()->toArray(),
        );
    }
}
