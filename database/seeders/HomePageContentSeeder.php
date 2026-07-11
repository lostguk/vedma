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

        $disk = Storage::disk('public');

        if (! $disk->exists('home')) {
            $disk->makeDirectory('home');
        }

        foreach ($files as $file) {
            $sourcePath = database_path("seeders/homepage-images/{$file}");
            $targetPath = $disk->path("home/{$file}");

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

        // Привязываем 3 наполненные категории к главной странице
        $categories = Category::query()
            ->whereIn('slug', ['tonkie-svechi', 'tsvetnye-svechi', 'ritualnye-svechi'])
            ->where('is_visible', true)
            ->get();

        // $categories->dd();

        if ($categories->isNotEmpty()) {
            $order = ['tonkie-svechi', 'tsvetnye-svechi', 'ritualnye-svechi'];
            $ids = collect($order)
                ->map(fn (string $slug) => $categories->firstWhere('slug', $slug))
                ->filter()
                ->pluck('id')
                ->all();
            $homePageContent->categories()->sync($ids);
        }
    }
}
