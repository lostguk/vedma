<?php

declare(strict_types=1);

use App\Services\DaData\AddressSuggestService;

it('accepts a house-level fias address', function (): void {
    expect(AddressSuggestService::isDeliverableSuggestion([
        'data' => ['fias_level' => 8, 'city' => 'Москва', 'street' => 'Ленина', 'house' => '1'],
    ]))->toBeTrue();
});

it('accepts a village with settlement and house without street', function (): void {
    expect(AddressSuggestService::isDeliverableSuggestion([
        'data' => ['fias_level' => 6, 'settlement' => 'Ивановка', 'street' => null, 'house' => '5'],
    ]))->toBeTrue();
});

it('rejects a city without house', function (): void {
    expect(AddressSuggestService::isDeliverableSuggestion([
        'data' => ['fias_level' => 4, 'city' => 'Москва', 'street' => null, 'house' => null],
    ]))->toBeFalse();
});

it('rejects a city street without house', function (): void {
    expect(AddressSuggestService::isDeliverableSuggestion([
        'data' => ['fias_level' => 7, 'city' => 'Москва', 'street' => 'Ленина', 'house' => null],
    ]))->toBeFalse();
});

it('rejects a city house without street', function (): void {
    expect(AddressSuggestService::isDeliverableSuggestion([
        'data' => ['fias_level' => 0, 'city' => 'Москва', 'street' => '', 'house' => '1'],
    ]))->toBeFalse();
});
