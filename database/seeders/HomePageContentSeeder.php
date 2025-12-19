<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\HomePageContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class HomePageContentSeeder extends Seeder
{
    public function run(): void
    {
        $files = [
            'hero_image.png',
            'about_left_image.png',
            'about_right_image.png',
            'about_trust_feature_1_image.png',
            'about_trust_feature_2_image.png',
            'about_trust_feature_3_image.png',
        ];

        if (! Storage::exists('home')) {
            Storage::makeDirectory('home');
        }

        foreach ($files as $file) {
            $sourcePath = database_path("seeders/homepage-images/{$file}");
            $targetPath = Storage::path("home/{$file}");

            if (! file_exists($targetPath) && file_exists($sourcePath)) {
                File::copy($sourcePath, $targetPath);
            }
        }

        $payload = HomePageContent::factory()->make()->toArray();

        // Единственная запись с id = 1
        $homePageContent = HomePageContent::query()->updateOrCreate(
            ['id' => 1],
            $payload,
        );

        // Привязываем категории к главной странице
        // Ищем корневые категории (без parent_id) или категорию "Все свечи"
        $categories = Category::query()
            ->where(function ($query) {
                $query->whereNull('parent_id')
                    ->orWhere('slug', 'vse-svechi');
            })
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        if ($categories->isNotEmpty()) {
            $sortOrder = 1;
            foreach ($categories as $category) {
                $homePageContent->categories()->syncWithoutDetaching([
                    $category->id => ['sort_order' => $sortOrder++],
                ]);
            }
        }
    }
}
