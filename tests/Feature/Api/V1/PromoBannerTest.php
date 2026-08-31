<?php

declare(strict_types=1);

use App\Models\HomePageContent;
use App\Models\PromoBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('includes the visible promo banner on the home page', function (): void {
    HomePageContent::factory()->create();

    PromoBanner::factory()->active()->create([
        'kicker' => 'Особое предложение',
        'title' => 'Скидка на свечи',
        'discount_value' => '−20%',
        'promo_code' => 'VEDMA20',
        'button_url' => '/catalog',
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addWeek(),
    ]);

    $response = $this->getJson(route('api.v1.home.show'));

    $response->assertSuccessful()
        ->assertJsonPath('data.promo.title', 'Скидка на свечи')
        ->assertJsonPath('data.promo.discount_value', '−20%')
        ->assertJsonPath('data.promo.promo_code', 'VEDMA20')
        ->assertJsonPath('data.promo.button_url', '/catalog');
});

it('hides inactive or out-of-schedule promo banners', function (): void {
    HomePageContent::factory()->create();

    PromoBanner::factory()->create([
        'is_active' => false,
        'title' => 'Скрытая скидка',
    ]);

    PromoBanner::factory()->active()->create([
        'title' => 'Ещё не началась',
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addWeek(),
    ]);

    PromoBanner::factory()->active()->create([
        'title' => 'Уже закончилась',
        'starts_at' => now()->subWeek(),
        'ends_at' => now()->subDay(),
    ]);

    $response = $this->getJson(route('api.v1.home.show'));

    $response->assertSuccessful()
        ->assertJsonPath('data.promo', null);
});

it('deactivates previous banners when a new one is activated', function (): void {
    $first = PromoBanner::factory()->active()->create(['title' => 'Первая']);
    $second = PromoBanner::factory()->active()->create(['title' => 'Вторая']);

    expect($first->fresh()->is_active)->toBeFalse()
        ->and($second->fresh()->is_active)->toBeTrue();
});
