<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PromoBanner;
use Illuminate\Database\Seeder;

final class PromoBannerSeeder extends Seeder
{
    public function run(): void
    {
        PromoBanner::query()->updateOrCreate(
            ['title' => 'Скидка на ритуальные свечи'],
            [
                'is_active' => true,
                'kicker' => 'Особое предложение',
                'title' => 'Скидка на ритуальные свечи',
                'subtitle' => 'Авторские свечи с травами и пчелиным воском — по специальной цене, пока длится лунный цикл.',
                'discount_value' => '−20%',
                'discount_caption' => 'на свечи',
                'promo_code' => 'VEDMA20',
                'button_text' => 'Выбрать свечи',
                'button_url' => '/catalog',
                'starts_at' => null,
                'ends_at' => now()->addMonth(),
            ],
        );
    }
}
