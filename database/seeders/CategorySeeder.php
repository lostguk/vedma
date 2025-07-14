<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $icons = collect([
            'candle1.svg',
            'candle2.svg',
            'candle3.svg',
        ]);

        // Ensure seed-icons directory exists
        if (! Storage::exists('seed-icons')) {
            Storage::makeDirectory('seed-icons');
        }

        // Copy icon files to storage if they don't exist
        foreach ($icons as $icon) {
            $sourcePath = database_path("seeders/icons/{$icon}");
            $targetPath = Storage::path("seed-icons/{$icon}");

            if (! file_exists($targetPath) && file_exists($sourcePath)) {
                File::copy($sourcePath, $targetPath);
            }
        }

        // =========================
        // ЯВНАЯ СТРУКТУРА КАТЕГОРИЙ
        // =========================

        // 1. Корневая категория
        $allCandles = Category::factory()->create([
            'name' => 'Все свечи',
            'slug' => 'vse-svechi',
            'sort_order' => 1,
            'is_visible' => true,
            'meta_title' => 'Все свечи - Магазин свечей',
            'meta_description' => 'Большой выбор свечей для любых целей. Ритуальные, земляные, цветные и другие виды свечей с доставкой.',
        ]);
        $this->addIconToCategory($allCandles, $icons->random());

        // 2. Дети корневой категории
        $ritualCandles = Category::factory()->create([
            'name' => 'Ритуальные свечи',
            'slug' => 'ritualnye-svechi',
            'sort_order' => 1,
            'is_visible' => true,
            'meta_title' => 'Ритуальные свечи - Магазин свечей',
            'meta_description' => 'Ритуальные свечи для различных обрядов и церемоний. Широкий выбор цветов и размеров.',
            'parent_id' => $allCandles->id,
        ]);
        $this->addIconToCategory($ritualCandles, $icons->random());

        $earthCandles = Category::factory()->create([
            'name' => 'Земляные свечи',
            'slug' => 'zemlyanye-svechi',
            'sort_order' => 2,
            'is_visible' => true,
            'meta_title' => 'Земляные свечи - Магазин свечей',
            'meta_description' => 'Земляные свечи из натуральных материалов. Экологически чистые компоненты.',
            'parent_id' => $allCandles->id,
        ]);
        $this->addIconToCategory($earthCandles, $icons->random());

        $colorCandles = Category::factory()->create([
            'name' => 'Цветные свечи',
            'slug' => 'tsvetnye-svechi',
            'sort_order' => 3,
            'is_visible' => true,
            'meta_title' => 'Цветные свечи - Магазин свечей',
            'meta_description' => 'Яркие цветные свечи для праздников и декора. Различные цвета и формы.',
            'parent_id' => $allCandles->id,
        ]);
        $this->addIconToCategory($colorCandles, $icons->random());

        // 3. Дети "Ритуальных свечей"
        $moneyCandles = Category::factory()->create([
            'name' => 'Свечи для привлечения денег',
            'slug' => 'svechi-dlya-privlecheniya-deneg',
            'sort_order' => 1,
            'is_visible' => true,
            'meta_title' => 'Свечи для привлечения денег - Магазин свечей',
            'meta_description' => 'Специальные свечи для ритуалов на привлечение денег и благополучия.',
            'parent_id' => $ritualCandles->id,
        ]);
        $this->addIconToCategory($moneyCandles, $icons->random());

        $loveCandles = Category::factory()->create([
            'name' => 'Любовные свечи',
            'slug' => 'lyubovnye-svechi',
            'sort_order' => 2,
            'is_visible' => true,
            'meta_title' => 'Любовные свечи - Магазин свечей',
            'meta_description' => 'Свечи для любовных ритуалов и романтической атмосферы.',
            'parent_id' => $ritualCandles->id,
        ]);
        $this->addIconToCategory($loveCandles, $icons->random());

        // =========================
        // Нет циклов, нет вложения одной и той же категории в потомка
        // =========================

        // Добавляем тестовые изображения только в dev-окружении
        if (app()->environment('local', 'development')) {
            // Здесь можно добавить код для добавления тестовых изображений
            // через Media Library, когда будут реальные файлы
        }
    }

    private function addIconToCategory(Category $category, string $iconName): void
    {
        $iconPath = Storage::path("seed-icons/{$iconName}");
        if (file_exists($iconPath)) {
            $category->addMedia($iconPath)
                ->preservingOriginal()
                ->toMediaCollection('icon');
        }
    }
}
