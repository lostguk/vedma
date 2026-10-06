<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;

final class CartRepository
{
    public function findByUserId(int $userId): ?Cart
    {
        return Cart::query()->where('user_id', $userId)->first();
    }

    public function lockByUserId(int $userId): ?Cart
    {
        $cart = Cart::query()->where('user_id', $userId)->lockForUpdate()->first();

        return $cart instanceof Cart ? $cart : null;
    }

    public function createForUser(int $userId): Cart
    {
        return Cart::query()->create(['user_id' => $userId]);
    }

    /**
     * @param  list<array{product: Product, qty: int}>  $lines
     */
    public function replaceItems(Cart $cart, array $lines): void
    {
        $productIds = array_map(
            static fn (array $line): int => $line['product']->id,
            $lines,
        );

        if ($productIds === []) {
            $cart->items()->delete();

            return;
        }

        $cart->items()->whereNotIn('product_id', $productIds)->delete();

        foreach ($lines as $line) {
            $cart->items()->updateOrCreate(
                ['product_id' => $line['product']->id],
                ['quantity' => $line['qty']],
            );
        }
    }
}
