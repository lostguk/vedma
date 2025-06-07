<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        PromoCode::factory(5)->create()->each(function ($promoCode) use ($categories) {
            $promoCode->categories()->attach($categories->random(rand(1, 3))->pluck('id')->toArray());
        });
    }
}
