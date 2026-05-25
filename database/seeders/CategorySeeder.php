<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

final class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        // 2. Дети корневой категории
        $ritualCandles = Category::factory()->create([
            'name' => 'Ритуальные свечи',
            'slug' => 'ritualnye-svechi',

            'is_visible' => true,
            'description' => 'Свечи для обрядов и направленных практик',
            'parent_id' => $allCandles->id,
        ]);

        Category::factory()->create([
            'name' => 'Земляные свечи',
            'slug' => 'zemlyanye-svechi',

            'is_visible' => true,
            'description' => 'Земляные свечи из натуральных материалов с природными минералами и земной энергией. Идеальны для ритуалов на устойчивость, стабильность и процветание.',
            'parent_id' => $allCandles->id,
        ]);

        Category::factory()->create([
            'name' => 'Цветные свечи',
            'slug' => 'tsvetnye-svechi',

            'is_visible' => true,
            'description' => 'Свечи силы в каждом оттенке',
            'parent_id' => $allCandles->id,
        ]);

        Category::factory()->create([
            'name' => 'Тонкие свечи',
            'slug' => 'tonkie-svechi',

            'is_visible' => true,
            'description' => 'Восковые свечи для Таро, медитаций и ритуалов',
            'parent_id' => $allCandles->id,
        ]);

        // 3. Дети "Ритуальных свечей"
        Category::factory()->create([
            'name' => 'Свечи для привлечения денег',
            'slug' => 'svechi-dlya-privlecheniya-deneg',

            'is_visible' => true,
            'description' => 'Свечи для ритуалов на привлечение денег и финансового благополучия. Созданы с использованием колдовских масел и трав, помогают открыть финансовые потоки и привлечь изобилие.',
            'parent_id' => $ritualCandles->id,
        ]);

        Category::factory()->create([
            'name' => 'Любовные свечи',
            'slug' => 'lyubovnye-svechi',

            'is_visible' => true,
            'description' => 'Свечи для любовных ритуалов и привлечения партнера. Созданы с использованием трав и масел, связанных с энергией Венеры, помогают открыть сердце и укрепить отношения.',
            'parent_id' => $ritualCandles->id,
        ]);

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
}
