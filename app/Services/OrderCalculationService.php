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
     * @param  PromoCode|null  $promoCode
     * @param  string|null  $promoCodeInput
     * @return array{items: array<int, array>, total_without_discount: float, total_with_discount: float, promo_code_status: string}
     */
    public function calculate(Collection $products, array $items, ?PromoCode $promoCode = null, ?string $promoCodeInput = null): array
    {
        $result = [];
        $itemsById = collect($items)->keyBy('id');
        $promoCategories = $promoCode?->categories->pluck('id')->all() ?? [];
        $totalWithoutDiscount = 0.0;
        $totalWithDiscount = 0.0;
        $hasDiscountedItems = false;

        foreach ($products as $product) {
            $count = (int) ($itemsById[$product->id]['count'] ?? 1);
            $price = $product->price;
            $oldPrice = $product->old_price ?? $product->price;
            $discounted = false;
            $originalPrice = $price;

            // Применяем промокод, если он активен и товар в нужной категории
            if ($promoCode && $product->categories->pluck('id')->intersect($promoCategories)->isNotEmpty()) {
                if ($promoCode->discount_type === 'percent') {
                    $price = round($price * (1 - $promoCode->discount_value / 100), 2);
                } elseif ($promoCode->discount_type === 'fixed') {
                    $price = max(0, round($price - $promoCode->discount_value, 2));
                }
                $discounted = true;
                $hasDiscountedItems = true;
            }

            $itemTotalWithoutDiscount = $originalPrice * $count;
            $itemTotalWithDiscount = $price * $count;

            $totalWithoutDiscount += $itemTotalWithoutDiscount;
            $totalWithDiscount += $itemTotalWithDiscount;

            $result[] = array_merge(
                $product->only([
                    'id', 'name', 'slug', 'description', 'price', 'old_price', 'weight', 'width', 'height', 'length', 'is_new', 'is_bestseller', 'sort_order', 'images_urls',
                ]),
                [
                    'count' => $count,
                    'summery' => $itemTotalWithDiscount,
                    'summery_old' => $oldPrice * $count,
                    'discounted' => $discounted,
                ]
            );
        }

        // Определяем статус промокода
        $promoCodeStatus = $this->determinePromoCodeStatus($promoCode, $promoCodeInput, $hasDiscountedItems);

        return [
            'items' => $result,
            'total_without_discount' => round($totalWithoutDiscount, 2),
            'total_with_discount' => round($totalWithDiscount, 2),
            'promo_code_status' => $promoCodeStatus,
        ];
    }

    /**
     * Определить статус промокода
     *
     * @param  PromoCode|null  $promoCode
     * @param  string|null  $promoCodeInput
     * @param  bool  $hasDiscountedItems
     * @return string
     */
    private function determinePromoCodeStatus(?PromoCode $promoCode, ?string $promoCodeInput, bool $hasDiscountedItems): string
    {
        // Если промокод не был передан
        if ($promoCodeInput === null || $promoCodeInput === '') {
            return 'not_exists';
        }

        // Если промокод не найден или не активен
        if ($promoCode === null) {
            return 'not_exists';
        }

        // Если промокод существует, но не применился к товарам
        if (! $hasDiscountedItems) {
            return 'not_applied';
        }

        // Промокод существует и применился
        return 'applied';
    }
}
