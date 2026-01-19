<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;

it('возвращает подсказки адреса через dadata', function (): void {
    config(['services.dadata.token' => 'test-token']);

    Http::fake([
        'suggestions.dadata.ru/*' => function ($request) {
            expect($request->header('Authorization')[0] ?? null)->toBe('Token test-token');

            $payload = $request->data();
            expect($payload['query'] ?? null)->toBe('москва хабар');
            expect($payload['count'] ?? null)->toBe(2);
            expect($payload['language'] ?? null)->toBe('ru');
            expect($payload['division'] ?? null)->toBe('administrative');

            return Http::response([
                'suggestions' => [
                    ['value' => 'г Москва, ул Хабаровская'],
                ],
            ], 200);
        },
    ]);

    $response = $this->postJson('/api/v1/order/address/suggest', [
        'query' => 'москва хабар',
        'count' => 2,
        'language' => 'ru',
        'division' => 'administrative',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.suggestions.0.value', 'г Москва, ул Хабаровская');
});

it('валидация запроса dadata требует query', function (): void {
    $response = $this->postJson('/api/v1/order/address/suggest', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['query']);
});
