<?php

declare(strict_types=1);

namespace App\Services\DaData;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final readonly class AddressSuggestService
{
    private const string DEFAULT_URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';

    public function suggest(string $query, ?int $count = null, ?string $language = null, ?string $division = null): array
    {
        $token = (string) config('services.dadata.token');
        if ($token === '') {
            throw new RuntimeException('Не настроен API-ключ DaData.');
        }

        $payload = ['query' => $query];

        if ($count !== null) {
            $payload['count'] = $count;
        }

        if ($language !== null) {
            $payload['language'] = $language;
        }

        if ($division !== null) {
            $payload['division'] = $division;
        }

        $url = (string) config('services.dadata.address_suggest_url');
        if ($url === '') {
            $url = self::DEFAULT_URL;
        }

        $response = Http::acceptJson()
            ->withHeaders([
                'Authorization' => 'Token '.$token,
            ])
            ->post($url, $payload);

        if ($response->failed()) {
            Log::error('DaData address suggest error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Не удалось получить подсказки адреса.');
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    public function isDeliverableAddress(string $address): bool
    {
        $result = $this->suggest(trim($address), 1);
        $suggestion = $result['suggestions'][0] ?? null;

        return is_array($suggestion) && self::isDeliverableSuggestion($suggestion);
    }

    /**
     * @param  array<string, mixed>  $suggestion
     */
    public static function isDeliverableSuggestion(array $suggestion): bool
    {
        $data = $suggestion['data'] ?? null;
        if (! is_array($data)) {
            return false;
        }

        $level = (int) ($data['fias_level'] ?? 0);
        if ($level >= 8) {
            return true;
        }

        $city = trim((string) ($data['city'] ?? ''));
        $settlement = trim((string) ($data['settlement'] ?? ''));
        $street = trim((string) ($data['street'] ?? ''));
        $house = trim((string) ($data['house'] ?? ''));

        if (($city === '' && $settlement === '') || $house === '') {
            return false;
        }

        return ! ($city !== '' && $street === '');
    }
}
