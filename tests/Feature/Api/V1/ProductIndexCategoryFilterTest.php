<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns products from selected category and all its descendants', function (): void {
    // Create category tree: parent -> child -> grandchild
    $parent = Category::factory()->create([
        'name' => 'Parent',
        'slug' => 'parent',
    ]);

    $child = Category::factory()->child($parent)->create([
        'name' => 'Child',
        'slug' => 'child',
    ]);

    $grandchild = Category::factory()->child($child)->create([
        'name' => 'Grandchild',
        'slug' => 'grandchild',
    ]);

    // Products in child and grandchild categories
    $productInChild = Product::factory()->create(['name' => 'In Child']);
    $productInChild->categories()->attach($child->id);

    $productInGrandchild = Product::factory()->create(['name' => 'In Grandchild']);
    $productInGrandchild->categories()->attach($grandchild->id);

    // Unrelated product
    $otherCategory = Category::factory()->create(['slug' => 'other']);
    $unrelatedProduct = Product::factory()->create(['name' => 'Unrelated']);
    $unrelatedProduct->categories()->attach($otherCategory->id);

    // Request products by the mid-tree category (child)
    $response = $this->getJson(route('api.v1.products.index', ['category' => $child->slug]));

    $response->assertSuccessful();

    $returnedIds = collect($response->json('data'))
        ->pluck('id')
        ->all();

    expect($returnedIds)
        ->toContain($productInChild->id)
        ->toContain($productInGrandchild->id)
        ->not->toContain($unrelatedProduct->id);
});
