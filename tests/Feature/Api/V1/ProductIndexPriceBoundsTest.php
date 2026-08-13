<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns catalog-wide price bounds in products index meta', function (): void {
    Product::factory()->create(['price' => 80]);
    Product::factory()->create(['price' => 250]);
    Product::factory()->create(['price' => 150]);

    $response = $this->getJson(route('api.v1.products.index'));

    $response
        ->assertOk()
        ->assertJsonPath('meta.price_min', 80)
        ->assertJsonPath('meta.price_max', 250);
});

it('keeps price bounds independent from price_from and price_to filters', function (): void {
    Product::factory()->create(['price' => 80]);
    Product::factory()->create(['price' => 150]);
    Product::factory()->create(['price' => 250]);

    $response = $this->getJson(route('api.v1.products.index', [
        'price_from' => 100,
        'price_to' => 200,
    ]));

    $response
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('meta.price_min', 80)
        ->assertJsonPath('meta.price_max', 250);
});

it('narrows price bounds to the selected category', function (): void {
    $candles = Category::factory()->create(['slug' => 'candles']);
    $sets = Category::factory()->create(['slug' => 'sets']);

    $cheapCandle = Product::factory()->create(['price' => 40]);
    $cheapCandle->categories()->attach($candles->id);

    $expensiveCandle = Product::factory()->create(['price' => 900]);
    $expensiveCandle->categories()->attach($candles->id);

    $expensiveSet = Product::factory()->create(['price' => 5000]);
    $expensiveSet->categories()->attach($sets->id);

    $response = $this->getJson(route('api.v1.products.index', [
        'category' => 'candles',
    ]));

    $response
        ->assertOk()
        ->assertJsonPath('meta.price_min', 40)
        ->assertJsonPath('meta.price_max', 900);
});

it('returns null price bounds when catalog is empty', function (): void {
    $response = $this->getJson(route('api.v1.products.index'));

    $response
        ->assertOk()
        ->assertJsonPath('meta.price_min', null)
        ->assertJsonPath('meta.price_max', null);
});
