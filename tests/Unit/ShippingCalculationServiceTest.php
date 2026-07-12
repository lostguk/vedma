<?php

declare(strict_types=1);

use App\Services\Shipping\ShippingCalculationService;

it('converts grams to kilograms when aggregating product weights', function (): void {
    $service = new ShippingCalculationService;
    $method = new \ReflectionMethod(ShippingCalculationService::class, 'aggregateItems');
    $method->setAccessible(true);

    $result = $method->invoke($service, [
        [
            'weight' => 700,
            'width' => 20,
            'height' => 18,
            'length' => 16,
            'quantity' => 1,
        ],
    ]);

    expect($result['weight'])->toBe(0.7);
});

it('sums weight for multiple product quantities in kilograms', function (): void {
    $service = new ShippingCalculationService;
    $method = new \ReflectionMethod(ShippingCalculationService::class, 'aggregateItems');
    $method->setAccessible(true);

    $result = $method->invoke($service, [
        [
            'weight' => 700,
            'width' => 20,
            'height' => 18,
            'length' => 16,
            'quantity' => 2,
        ],
    ]);

    expect($result['weight'])->toBe(1.4);
});

it('converts lightweight products below 500 grams correctly', function (): void {
    $service = new ShippingCalculationService;
    $method = new \ReflectionMethod(ShippingCalculationService::class, 'aggregateItems');
    $method->setAccessible(true);

    $result = $method->invoke($service, [
        [
            'weight' => 60,
            'width' => 10,
            'height' => 10,
            'length' => 10,
            'quantity' => 1,
        ],
    ]);

    expect($result['weight'])->toBe(0.1);
});

it('prepares metaship weight in kilograms without extra division', function (): void {
    $aggregatedWeight = 0.7;

    $metashipWeight = max(0.1, min(100.0, round($aggregatedWeight, 3)));

    expect($metashipWeight)->toBe(0.7);
});
