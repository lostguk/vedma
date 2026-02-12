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
     * @return array{items: array<int, array>, total_without_discount: int, total_with_discount: int, promo_code_status: string}
     */
    public function calculate(Collection $products, array $items, ?PromoCode $promoCode = null, ?string $promoCodeInput = null): array
    {
        $result = [];
        $itemsById = collect($items)->keyBy('id');
        $promoCategories = $promoCode?->categories->pluck('id')->all() ?? [];
        $totalWithoutDiscount = 0;
        $totalWithDiscount = 0;
        $hasDiscountedItems = false;

        foreach ($products as $product) {
            $count = (int) ($itemsById[$product->id]['count'] ?? 1);
            $price = (int) round((float) $product->price);
            $oldPrice = (int) round((float) ($product->old_price ?? $product->price));
            $discounted = false;
            $originalPrice = $price;

            // Применяем промокод, если он активен и товар в нужной категории
            if ($promoCode && $product->categories->pluck('id')->intersect($promoCategories)->isNotEmpty()) {
                if ($promoCode->discount_type === 'percent') {
                    $price = (int) round($price * (1 - $promoCode->discount_value / 100));
                } elseif ($promoCode->discount_type === 'fixed') {
                    $price = max(0, (int) round($price - $promoCode->discount_value));
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
                    'price' => $price,
                    'old_price' => $oldPrice,
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
            'total_without_discount' => $totalWithoutDiscount,
            'total_with_discount' => $totalWithDiscount,
            'promo_code_status' => $promoCodeStatus,
        ];
    }

    /**
     * Определить статус промокода
     */
    private function determinePromoCodeStatus(?PromoCode $promoCode, ?string $promoCodeInput, bool $hasDiscountedItems): string
    {
        // Если промокод не был передан
        if ($promoCodeInput === null || $promoCodeInput === '') {
            return 'not_sent';
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
