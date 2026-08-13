<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\DemoCatalogProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates 200 demo products without media and replaces them on rerun', function (): void {
    $category = Category::factory()->create(['is_visible' => true]);

    $this->seed(DemoCatalogProductSeeder::class);

    $demoProducts = Product::query()->where('slug', 'like', DemoCatalogProductSeeder::SLUG_PREFIX.'%')->get();

    expect($demoProducts)->toHaveCount(DemoCatalogProductSeeder::COUNT);
    expect($demoProducts->first()->getMedia(Product::IMAGES_COLLECTION))->toBeEmpty();
    expect($demoProducts->first()->categories()->pluck('categories.id'))->toContain($category->id);

    $firstId = $demoProducts->first()->id;

    $this->seed(DemoCatalogProductSeeder::class);

    $rerun = Product::query()->where('slug', 'like', DemoCatalogProductSeeder::SLUG_PREFIX.'%')->get();

    expect($rerun)->toHaveCount(DemoCatalogProductSeeder::COUNT);
    expect($rerun->pluck('id'))->not->toContain($firstId);
});
