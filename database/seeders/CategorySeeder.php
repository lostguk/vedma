<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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

        Category::create([
            'id' => 1,
            'name' => 'Горные вершины',
            'slug' => 'gornie-vershini',

            'is_visible' => true,
        ]);

        // 1. Корневая категория
        $allCandles = Category::factory()->create([
            'name' => 'Все свечи',
            'slug' => 'vse-svechi',

            'is_visible' => true,
            'description' => 'Широкий ассортимент магических свечей для различных ритуалов, практик и обрядов. Каждая свеча создается как проводник намерения с использованием только природных компонентов.',
        ]);
        $this->addIconToCategory($allCandles, $icons->random());

        // 2. Дети корневой категории
        $ritualCandles = Category::factory()->create([
            'name' => 'Ритуальные свечи',
            'slug' => 'ritualnye-svechi',

            'is_visible' => true,
            'description' => 'Свечи для обрядов и направленных практик',
            'parent_id' => $allCandles->id,
        ]);
        $this->addIconToCategory($ritualCandles, $icons->random());

        $earthCandles = Category::factory()->create([
            'name' => 'Земляные свечи',
            'slug' => 'zemlyanye-svechi',

            'is_visible' => true,
            'description' => 'Земляные свечи из натуральных материалов с природными минералами и земной энергией. Идеальны для ритуалов на устойчивость, стабильность и процветание.',
            'parent_id' => $allCandles->id,
        ]);
        $this->addIconToCategory($earthCandles, $icons->random());

        $colorCandles = Category::factory()->create([
            'name' => 'Цветные свечи',
            'slug' => 'tsvetnye-svechi',

            'is_visible' => true,
            'description' => 'Свечи силы в каждом оттенке',
            'parent_id' => $allCandles->id,
        ]);
        $this->addIconToCategory($colorCandles, $icons->random());

        $thinCandles = Category::factory()->create([
            'name' => 'Тонкие свечи',
            'slug' => 'tonkie-svechi',

            'is_visible' => true,
            'description' => 'Восковые свечи для Таро, медитаций и ритуалов',
            'parent_id' => $allCandles->id,
        ]);
        $this->addIconToCategory($thinCandles, $icons->random());

        // 3. Дети "Ритуальных свечей"
        $moneyCandles = Category::factory()->create([
            'name' => 'Свечи для привлечения денег',
            'slug' => 'svechi-dlya-privlecheniya-deneg',

            'is_visible' => true,
            'description' => 'Свечи для ритуалов на привлечение денег и финансового благополучия. Созданы с использованием колдовских масел и трав, помогают открыть финансовые потоки и привлечь изобилие.',
            'parent_id' => $ritualCandles->id,
        ]);
        $this->addIconToCategory($moneyCandles, $icons->random());

        $loveCandles = Category::factory()->create([
            'name' => 'Любовные свечи',
            'slug' => 'lyubovnye-svechi',

            'is_visible' => true,
            'description' => 'Свечи для любовных ритуалов и привлечения партнера. Созданы с использованием трав и масел, связанных с энергией Венеры, помогают открыть сердце и укрепить отношения.',
            'parent_id' => $ritualCandles->id,
        ]);
        $this->addIconToCategory($loveCandles, $icons->random());

        // =========================
        // Категория «Услуги» (не считается в доставку)
        // =========================

        $services = Category::query()->updateOrCreate(['slug' => 'uslugi'], [
            'name' => 'Услуги',
            'slug' => 'uslugi',

            'is_visible' => true,
            'exclude_from_shipping' => true,
            'description' => 'Магические услуги: гадания, консультации, индивидуальные ритуалы. Товары из этой категории не требуют доставки.',
            'parent_id' => null,
        ]);
        $this->addIconToCategory($services, $icons->random());

        Category::query()->updateOrCreate(['slug' => 'gadaniya'], [
            'name' => 'Гадания',
            'slug' => 'gadaniya',

            'is_visible' => true,
            'description' => 'Индивидуальные гадания на картах Таро, рунах и оракулах. Расклады на любовь, карьеру, здоровье и духовное развитие.',
            'parent_id' => $services->id,
        ]);

        Category::query()->updateOrCreate(['slug' => 'konsultatsii'], [
            'name' => 'Консультации',
            'slug' => 'konsultatsii',

            'is_visible' => true,
            'description' => 'Персональные консультации по выбору свечей, масел и ритуальных практик. Подбор индивидуальной программы работы.',
            'parent_id' => $services->id,
        ]);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
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
