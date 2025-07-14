<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Support\Collection;

final class OrderCalculationService
{
    /**
     * Рассчитать итоговую стоимость товаров с учетом промокода
     *
     * @param  Collection<int, Product>  $products
     * @param  array<int, array{id: int, count: int}>  $items
     * @return array<int, array>
     */
    public function calculate(Collection $products, array $items, ?PromoCode $promoCode = null): array
    {
        $result = [];
        $itemsById = collect($items)->keyBy('id');
        $promoCategories = $promoCode?->categories->pluck('id')->all() ?? [];
        foreach ($products as $product) {
            $count = (int) ($itemsById[$product->id]['count'] ?? 1);
            $price = $product->price;
            $oldPrice = $product->old_price ?? $product->price;
            $discounted = false;
            // Применяем промокод, если он активен и товар в нужной категории
            if ($promoCode && $product->categories->pluck('id')->intersect($promoCategories)->isNotEmpty()) {
                if ($promoCode->discount_type === 'percent') {
                    $price = round($price * (1 - $promoCode->discount_value / 100), 2);
                } elseif ($promoCode->discount_type === 'fixed') {
                    $price = max(0, round($price - $promoCode->discount_value, 2));
                }
                $discounted = true;
            }
            $result[] = array_merge(
                $product->only([
                    'id', 'name', 'slug', 'description', 'price', 'old_price', 'weight', 'width', 'height', 'length', 'is_new', 'is_bestseller', 'sort_order', 'images_urls',
                ]),
                [
                    'count' => $count,
                    'summery' => $price * $count,
                    'summery_old' => $oldPrice * $count,
                    'discounted' => $discounted,
                ]
            );
        }

        return $result;
    }
}
