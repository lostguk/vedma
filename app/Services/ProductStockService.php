<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Api\InsufficientStockException;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

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
     * Вернуть остатки по позициям заказа. Повторный вызов — no-op.
     *
     * @return bool true, если остатки действительно вернули
     */
    public function restore(Order $order): bool
    {
        return (bool) DB::transaction(function () use ($order): bool {
            /** @var Order $locked */
            $locked = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->stock_released_at !== null) {
                return false;
            }

            $locked->loadMissing('items');

            $quantities = [];

            foreach ($locked->items as $item) {
                if ($item->product_id === null) {
                    continue;
                }

                $quantities[$item->product_id] = ($quantities[$item->product_id] ?? 0) + $item->count;
            }

            if ($quantities !== []) {
                $products = $this->lockLimitedStockProducts(array_keys($quantities));

                foreach ($quantities as $productId => $count) {
                    $product = $products->get($productId);

                    if ($product !== null) {
                        $product->increment('stock', $count);
                    }
                }
            }

            $locked->forceFill(['stock_released_at' => now()])->save();

            return true;
        });
    }

    /**
     * Отменить неоплаченный заказ и вернуть остатки на склад.
     */
    public function cancelAndRelease(Order $order): void
    {
        if (in_array($order->status, ['paid', 'refunded'], true)) {
            throw new RuntimeException('Нельзя отменить оплаченный заказ. Оформите возврат оплаты.');
        }

        if ($order->status === 'cancelled' && $order->hasReleasedStock()) {
            throw new RuntimeException('Заказ уже отменён, остатки возвращены.');
        }

        if (! $order->canCancelAndReleaseStock()) {
            throw new RuntimeException('Этот заказ нельзя отменить для возврата склада.');
        }

        DB::transaction(function () use ($order): void {
            $this->restore($order);

            /** @var Order $locked */
            $locked = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'cancelled') {
                $locked->update(['status' => 'cancelled']);
            }
        });
    }

    /**
     * Проставить флаг без инкремента — для исторических refunded, где склад уже вернули.
     */
    public function markReleasedWithoutRestore(Order $order): void
    {
        if ($order->stock_released_at !== null) {
            return;
        }

        $order->update(['stock_released_at' => now()]);
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
