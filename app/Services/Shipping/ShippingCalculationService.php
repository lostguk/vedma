<?php

declare(strict_types=1);

namespace App\Services\Shipping;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class ShippingCalculationService
{
    private const string AUTH_URL = 'https://api.metaship.ru/auth/access_token';

    private const string OFFERS_URL = 'https://api.metaship.ru/v2/offers';

    public function calculate(array $products, string $address): array
    {
        $shippableProducts = $this->filterShippableProducts($products);

        if (empty($shippableProducts)) {
            return [];
        }

        $bearer = $this->requestBearerToken();
        if ($bearer === null) {
            return [
                'error' => 'Не удалось получить bearer-токен Metaship',
            ];
        }

        $items = $this->mapProductsToItems($shippableProducts);
        $totals = $this->aggregateItems($items);
        $declaredValue = $this->calculateDeclaredValue($shippableProducts);

        $params = [
            'length' => (int) $totals['length'],
            'width' => (int) $totals['width'],
            'height' => (int) $totals['height'],
            'weight' => max(0.1, min(100.0, round((float) $totals['weight'], 3))) / 1000,
            'declaredValue' => $declaredValue,
            'address' => $address,
            'errors' => 1,
            'types[0]' => 'DeliveryPoint',
            'types[1]' => 'PostOffice',
            'types[2]' => 'Courier',
        ];

        $shopId = (string) config('services.metaship.shop_id');
        $warehouseId = (string) config('services.metaship.warehouse_id');

        if ($shopId !== '') {
            $params['shopId'] = $shopId;
        }
        if ($warehouseId !== '') {
            $params['warehouseId'] = $warehouseId;
        }

        Log::info('Metaship API request', [
            'url' => self::OFFERS_URL,
            'query' => $params,
        ]);

        $response = Http::acceptJson()
            ->withToken($bearer, 'Bearer')
            ->get(self::OFFERS_URL, $params);

        Log::info('Metaship API response', ['body' => $response->body() ?? '']);

        if ($response->failed()) {
            Log::error('Metaship API error', [
                'url' => self::OFFERS_URL,
                'status' => $response->status(),
                'query' => $params,
                'raw_body' => $response->body(),
            ]);

            return [];
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    /**
     * Рассчитать стоимость доставки для конкретного типа.
     * Вызывает MetaShip, фильтрует по delivery_type и возвращает минимальную цену.
     *
     * @param  array<int, array{id:int, quantity:int}>  $products
     * @return int|null Цена доставки в рублях или null если вариантов нет
     */
    public function calculatePriceForDeliveryType(array $products, string $address, string $deliveryType): ?int
    {
        $offers = $this->calculate($products, $address);

        if (empty($offers) || isset($offers['error'])) {
            return null;
        }

        return $this->findCheapestPrice($offers, $deliveryType);
    }

    /**
     * Найти минимальную цену из группированного ответа MetaShip по типу доставки.
     *
     * MetaShip возвращает объект, сгруппированный по типу:
     * {"DeliveryPoint": [{delivery: {code: "RussianPost"}, service: {total: "341.23"}, ...}], "PostOffice": [...]}
     *
     * @param  array<string, array<int, array<string, mixed>>>  $groupedOffers
     */
    public function findCheapestPrice(array $groupedOffers, string $deliveryType): ?int
    {
        $allOffers = collect($groupedOffers)
            ->filter(fn ($value): bool => is_array($value))
            ->flatMap(fn (array $group): array => $group);

        $filtered = $allOffers->filter(function (array $offer) use ($deliveryType): bool {
            $carrierCode = mb_strtolower($offer['delivery']['code'] ?? '');

            return match ($deliveryType) {
                'PostOffice' => str_contains($carrierCode, 'russianpost'),
                'Cdek' => str_contains($carrierCode, 'cdek'),
                default => false,
            };
        });

        if ($filtered->isEmpty()) {
            return null;
        }

        $minPrice = $filtered->min(fn (array $offer): ?float => isset($offer['service']['total'])
            ? (float) $offer['service']['total']
            : null
        );

        return $minPrice !== null ? (int) ceil($minPrice) : null;
    }

    /**
     * Отфильтровать товары, исключив те, чья категория помечена как exclude_from_shipping.
     *
     * @param  array<int, array{id:int, quantity:int}>  $products
     * @return array<int, array{id:int, quantity:int}>
     */
    private function filterShippableProducts(array $products): array
    {
        $ids = collect($products)->pluck('id')->all();

        $productsWithCategories = Product::query()
            ->whereIn('id', $ids)
            ->with('categories')
            ->get()
            ->keyBy('id');

        return collect($products)->filter(function (array $item) use ($productsWithCategories) {
            $product = $productsWithCategories[$item['id']] ?? null;
            if (! $product) {
                return false;
            }

            foreach ($product->categories as $category) {
                if ($category->isExcludedFromShipping()) {
                    return false;
                }
            }

            return true;
        })->values()->all();
    }

    private function requestBearerToken(): ?string
    {
        $clientId = (string) config('services.metaship.api_key');
        $clientSecret = (string) config('services.metaship.api_secret');
        if ($clientId === '' || $clientSecret === '') {
            Log::error('Metaship token request: missing client_id or client_secret');

            return null;
        }

        $response = Http::asForm()->post(self::AUTH_URL, [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if ($response->failed()) {
            Log::error('Metaship token error', [
                'status' => $response->status(),
                'raw_body' => $response->body(),
            ]);

            return null;
        }

        $token = $response->json('access_token');

        return is_string($token) && $token !== '' ? $token : null;
    }

    /**
     * @param  array<int, array{weight:float,width:float,height:float,length:float,quantity:int}>  $items
     * @return array{weight:float,width:float,height:float,length:float}
     */
    private function aggregateItems(array $items): array
    {
        $weightKg = 0.0;
        $width = 0.0;
        $height = 0.0;
        $length = 0.0;

        foreach ($items as $i) {
            $qty = max(1, (int) $i['quantity']);

            $w = (float) $i['weight'];
            $wInKg = $w > 500 ? $w / 1000.0 : $w;
            $weightKg += $wInKg * $qty;

            $width = max($width, (float) $i['width']);
            $height = max($height, (float) $i['height']);
            $length = max($length, (float) $i['length']);
        }

        $width = max(1, min(200, (int) round($width)));
        $height = max(1, min(200, (int) round($height)));
        $length = max(1, min(200, (int) round($length)));

        return [
            'weight' => max(0.1, round($weightKg, 3)),
            'width' => $width,
            'height' => $height,
            'length' => $length,
        ];
    }

    /** @param array<int, array{id:int, quantity:int}> $products */
    private function calculateDeclaredValue(array $products): float
    {
        $ids = collect($products)->pluck('id')->all();
        /** @var array<int, Product> $idToProduct */
        $idToProduct = Product::query()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id')
            ->all();

        $sum = 0.0;

        foreach ($products as $p) {
            $product = $idToProduct[$p['id']] ?? null;
            if ($product) {
                $sum += ((float) $product->price) * (int) $p['quantity'];
            }
        }

        return round($sum, 2);
    }

    /**
     * @param  array<int, array{id:int, quantity:int}>  $products
     * @return array<int, array{weight:float,width:float,height:float,length:float,quantity:int}>
     */
    private function mapProductsToItems(array $products): array
    {
        $ids = collect($products)->pluck('id')->all();
        /** @var array<int, Product> $idToProduct */
        $idToProduct = Product::query()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id')
            ->all();

        $items = [];

        foreach ($products as $productInput) {
            $product = $idToProduct[$productInput['id']] ?? null;
            if ($product === null) {
                continue;
            }

            $items[] = [
                'weight' => (float) $product->weight,
                'width' => (float) $product->width,
                'height' => (float) $product->height,
                'length' => (float) $product->length,
                'quantity' => (int) $productInput['quantity'],
            ];
        }

        return $items;
    }
}
