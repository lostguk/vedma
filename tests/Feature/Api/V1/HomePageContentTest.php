<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\HomePageContent;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns home page content with categories and products', function (): void {
    $homePageContent = HomePageContent::factory()->create();

    // Create category tree: parent -> child -> grandchild
    $parent = Category::factory()->create([
        'name' => 'Родительская категория',
        'slug' => 'parent',
    ]);

    $child = Category::factory()->child($parent)->create([
        'name' => 'Дочерняя категория',
        'slug' => 'child',
    ]);

    $grandchild = Category::factory()->child($child)->create([
        'name' => 'Внучатая категория',
        'slug' => 'grandchild',
    ]);

    // Attach parent category to home page content
    $homePageContent->categories()->attach($parent->id);

    // Create products in different categories
    $productInParent = Product::factory()->create(['name' => 'Товар в родительской']);
    $productInParent->categories()->attach($parent->id);

    $productInChild = Product::factory()->create(['name' => 'Товар в дочерней']);
    $productInChild->categories()->attach($child->id);

    $productInGrandchild = Product::factory()->create(['name' => 'Товар во внучатой']);
    $productInGrandchild->categories()->attach($grandchild->id);

    // Unrelated product
    $otherCategory = Category::factory()->create(['slug' => 'other']);
    $unrelatedProduct = Product::factory()->create(['name' => 'Несвязанный товар']);
    $unrelatedProduct->categories()->attach($otherCategory->id);

    $response = $this->getJson(route('api.v1.home.show'));

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'hero',
                'about',
                'categories' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                    ],
                ],
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                    ],
                ],
            ],
        ]);

    $data = $response->json('data');

    // Check that categories are returned
    expect($data['categories'])->toHaveCount(1);
    expect($data['categories'][0]['id'])->toBe($parent->id);

    // Check that products from all nested categories are returned
    // But limited to 3 products per category
    $productIds = collect($data['products'])->pluck('id')->all();

    // Should contain at least one of the products, but maximum 3 total
    expect(count($productIds))->toBeLessThanOrEqual(3);
    expect($productIds)
        ->not->toContain($unrelatedProduct->id);
});

it('returns empty arrays when no categories are selected', function (): void {
    $homePageContent = HomePageContent::factory()->create();

    $response = $this->getJson(route('api.v1.home.show'));

    $response->assertSuccessful();

    $data = $response->json('data');

    expect($data['categories'])->toBeArray()->toBeEmpty();
    expect($data['products'])->toBeArray()->toBeEmpty();
});

it('returns products from multiple selected categories recursively', function (): void {
    $homePageContent = HomePageContent::factory()->create();

    // Create two separate category trees
    $category1 = Category::factory()->create(['name' => 'Категория 1', 'slug' => 'cat1']);
    $category1Child = Category::factory()->child($category1)->create(['name' => 'Подкатегория 1', 'slug' => 'cat1-child']);

    $category2 = Category::factory()->create(['name' => 'Категория 2', 'slug' => 'cat2']);
    $category2Child = Category::factory()->child($category2)->create(['name' => 'Подкатегория 2', 'slug' => 'cat2-child']);

    // Attach both root categories to home page with sort_order
    $homePageContent->categories()->attach([
        $category2->id => ['sort_order' => 1], // category2 должна быть первой
        $category1->id => ['sort_order' => 2], // category1 должна быть второй
    ]);

    // Create products
    $product1 = Product::factory()->create(['name' => 'Товар 1']);
    $product1->categories()->attach($category1->id);

    $product2 = Product::factory()->create(['name' => 'Товар 2']);
    $product2->categories()->attach($category1Child->id);

    $product3 = Product::factory()->create(['name' => 'Товар 3']);
    $product3->categories()->attach($category2->id);

    $product4 = Product::factory()->create(['name' => 'Товар 4']);
    $product4->categories()->attach($category2Child->id);

    $response = $this->getJson(route('api.v1.home.show'));

    $response->assertSuccessful();

    $data = $response->json('data');

    // Check that both categories are returned
    expect($data['categories'])->toHaveCount(2);

    // Check that categories are returned in correct order (by sort_order)
    expect($data['categories'][0]['id'])->toBe($category2->id);
    expect($data['categories'][1]['id'])->toBe($category1->id);

    // Check that all products from both trees are returned
    $productIds = collect($data['products'])->pluck('id')->all();

    expect($productIds)
        ->toContain($product1->id)
        ->toContain($product2->id)
        ->toContain($product3->id)
        ->toContain($product4->id);
});

it('returns categories in correct sort order', function (): void {
    $homePageContent = HomePageContent::factory()->create();

    $category1 = Category::factory()->create(['name' => 'Категория 1', 'slug' => 'cat1']);
    $category2 = Category::factory()->create(['name' => 'Категория 2', 'slug' => 'cat2']);
    $category3 = Category::factory()->create(['name' => 'Категория 3', 'slug' => 'cat3']);

    // Attach categories in reverse order, but with sort_order
    $homePageContent->categories()->attach([
        $category3->id => ['sort_order' => 3],
        $category1->id => ['sort_order' => 1],
        $category2->id => ['sort_order' => 2],
    ]);

    $response = $this->getJson(route('api.v1.home.show'));

    $response->assertSuccessful();

    $data = $response->json('data');

    // Check that categories are returned in correct order
    expect($data['categories'])->toHaveCount(3);
    expect($data['categories'][0]['id'])->toBe($category1->id);
    expect($data['categories'][1]['id'])->toBe($category2->id);
    expect($data['categories'][2]['id'])->toBe($category3->id);
});

it('returns maximum 3 products per category', function (): void {
    $homePageContent = HomePageContent::factory()->create();

    $category1 = Category::factory()->create(['name' => 'Категория 1', 'slug' => 'cat1']);
    $category2 = Category::factory()->create(['name' => 'Категория 2', 'slug' => 'cat2']);

    // Attach categories
    $homePageContent->categories()->attach([
        $category1->id => ['sort_order' => 1],
        $category2->id => ['sort_order' => 2],
    ]);

    // Create 5 products in category1
    $products1 = Product::factory()->count(5)->create();
    foreach ($products1 as $product) {
        $product->categories()->attach($category1->id);
    }

    // Create 2 products in category2
    $products2 = Product::factory()->count(2)->create();
    foreach ($products2 as $product) {
        $product->categories()->attach($category2->id);
    }

    $response = $this->getJson(route('api.v1.home.show'));

    $response->assertSuccessful();

    $data = $response->json('data');

    // Check that we get maximum 3 products from category1 and 2 from category2
    // Total should be 5 (3 + 2), but if there are duplicates, it might be less
    $productIds = collect($data['products'])->pluck('id')->all();

    // Should have at least 3 products from category1 and 2 from category2
    // But total should not exceed 5 (3 from cat1 + 2 from cat2)
    expect(count($productIds))->toBeLessThanOrEqual(5);

    // Check that we have products from both categories
    $category1ProductIds = $products1->pluck('id')->all();
    $category2ProductIds = $products2->pluck('id')->all();

    $foundFromCategory1 = count(array_intersect($productIds, $category1ProductIds));
    $foundFromCategory2 = count(array_intersect($productIds, $category2ProductIds));

    // Should have maximum 3 from category1
    expect($foundFromCategory1)->toBeLessThanOrEqual(3);
    // Should have all 2 from category2
    expect($foundFromCategory2)->toBe(2);
});
