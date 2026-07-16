<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Api\InsufficientStockException;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

final readonly class ProductStockService
{
    /**
     * Списать остатки по позициям заказа.
     *
     * @param  array<int, array{id: int, count: int}>  $items
     */
    public function deduct(array $items): void
    {
        $quantities = $this->aggregateQuantities($items);

        if ($quantities === []) {
            return;
        }

        $products = $this->lockLimitedStockProducts(array_keys($quantities));

        foreach ($quantities as $productId => $count) {
            $product = $products->get($productId);

            if ($product === null) {
                continue;
            }

            if ($product->stock < $count) {
                throw new InsufficientStockException($product->name, $product->stock);
            }
        }

        foreach ($quantities as $productId => $count) {
            $product = $products->get($productId);

            if ($product !== null) {
                $product->decrement('stock', $count);
            }
        }
    }

    /**
     * Вернуть остатки по позициям заказа.
     */
    public function restore(Order $order): void
    {
        $order->loadMissing('items');

        $quantities = [];

        foreach ($order->items as $item) {
            if ($item->product_id === null) {
                continue;
            }

            $quantities[$item->product_id] = ($quantities[$item->product_id] ?? 0) + $item->count;
        }

        if ($quantities === []) {
            return;
        }

        Product::query()
            ->whereIn('id', array_keys($quantities))
            ->whereNotNull('stock')
            ->get()
            ->each(function (Product $product) use ($quantities): void {
                $product->increment('stock', $quantities[$product->id]);
            });
    }

    /**
     * @param  array<int, array{id: int, count: int}>  $items
     * @return array<int, int>
     */
    private function aggregateQuantities(array $items): array
    {
        $quantities = [];

        foreach ($items as $item) {
            $productId = (int) $item['id'];
            $quantities[$productId] = ($quantities[$productId] ?? 0) + (int) $item['count'];
        }

        return array_filter($quantities, fn (int $count): bool => $count > 0);
    }

    /**
     * @param  array<int>  $productIds
     * @return Collection<int, Product>
     */
    private function lockLimitedStockProducts(array $productIds): Collection
    {
        return Product::query()
            ->whereIn('id', $productIds)
            ->whereNotNull('stock')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
    }
}
