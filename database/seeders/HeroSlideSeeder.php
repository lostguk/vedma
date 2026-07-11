<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $disk = Storage::disk('public');

        if (! $disk->exists('hero-slides')) {
            $disk->makeDirectory('hero-slides');
        }

        $slides = [
            [
                'title' => 'Ритуальные свечи',
                'accent' => 'ручной работы',
                'subtitle' => 'Каждая свеча создаётся с намерением, колдовскими травами и натуральным воском',
                'button_text' => 'Смотреть свечи',
                'button_url' => '/catalog',
                'image_file' => 'hero-slide-candles.webp',

            ],
            [
                'title' => 'Колдовские масла',
                'accent' => 'и зелья',
                'subtitle' => 'Закрытые рецептуры на основе редких трав, пчелиного воска и эфирных масел',
                'button_text' => 'Выбрать зелье',
                'button_url' => '/catalog',
                'image_file' => 'hero-slide-potions.webp',

            ],
            [
                'title' => 'Обереги и амулеты',
                'accent' => 'для защиты',
                'subtitle' => 'Артефакты силы, созданные по традиционным рецептам для вашей защиты и гармонии',
                'button_text' => 'Выбрать артефакт',
                'button_url' => '/catalog',
                'image_file' => 'hero-slide-artifacts.webp',

            ],
        ];

        foreach ($slides as $slideData) {
            $imageFile = $slideData['image_file'];
            unset($slideData['image_file']);

            $sourcePath = database_path("seeders/hero-slides/{$imageFile}");
            $storagePath = "hero-slides/{$imageFile}";

            if (file_exists($sourcePath) && ! $disk->exists($storagePath)) {
                File::copy($sourcePath, $disk->path($storagePath));
            }

            $slideData['image_path'] = $disk->exists($storagePath) ? $storagePath : null;
            $slideData['is_active'] = true;

            HeroSlide::query()->updateOrCreate(
                ['title' => $slideData['title']],
                $slideData,
            );
        }
    }
}
