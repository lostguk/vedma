<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\V1\CartLineResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

final readonly class CartService
{
    public function __construct(
        private CartRepository $cartRepository,
        private ProductRepository $productRepository,
    ) {}

    /**
     * @return array{updated_at: string|null, items: list<array<string, mixed>>, removed: list<array{id: int, reason: string}>}
     */
    public function get(User $user): array
    {
        $this->ensureCart($user);

        return DB::transaction(function () use ($user): array {
            $cart = $this->lockedCart($user);
            $cart->load('items');

            $quantities = [];
            foreach ($cart->items as $item) {
                $quantities[(int) $item->product_id] = (int) $item->quantity;
            }

            [$lines, $removed] = $this->resolve($quantities);

            if ($this->linesChanged($quantities, $lines)) {
                $this->cartRepository->replaceItems($cart, $lines);
                $cart->touch();
            }

            return $this->present($cart, $lines, $removed);
        });
    }

    /**
     * @param  list<array{id: int, quantity: int}>  $items
     * @return array{updated_at: string|null, items: list<array<string, mixed>>, removed: list<array{id: int, reason: string}>}
     */
    public function replace(User $user, array $items): array
    {
        $this->ensureCart($user);

        return DB::transaction(function () use ($user, $items): array {
            $cart = $this->lockedCart($user);
            [$lines, $removed] = $this->resolve($this->normalize($items));
            $this->cartRepository->replaceItems($cart, $lines);
            $cart->touch();

            return $this->present($cart, $lines, $removed);
        });
    }

    /**
     * @param  list<array{id: int, quantity: int}>  $items
     * @return array{updated_at: string|null, items: list<array<string, mixed>>, removed: list<array{id: int, reason: string}>}
     */
    public function merge(User $user, array $items): array
    {
        $this->ensureCart($user);

        return DB::transaction(function () use ($user, $items): array {
            $cart = $this->lockedCart($user);
            $cart->load('items');

            $quantities = [];
            foreach ($cart->items as $item) {
                $quantities[(int) $item->product_id] = (int) $item->quantity;
            }

            foreach ($this->normalize($items) as $productId => $quantity) {
                $quantities[$productId] = ($quantities[$productId] ?? 0) + $quantity;
            }

            [$lines, $removed] = $this->resolve($quantities);
            $this->cartRepository->replaceItems($cart, $lines);
            $cart->touch();

            return $this->present($cart, $lines, $removed);
        });
    }

    public function clear(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $cart = $this->cartRepository->lockByUserId($user->id);
            if (! $cart instanceof Cart) {
                return;
            }

            $cart->items()->delete();
            $cart->touch();
        });
    }

    private function ensureCart(User $user): void
    {
        if ($this->cartRepository->findByUserId($user->id) instanceof Cart) {
            return;
        }

        try {
            $this->cartRepository->createForUser($user->id);
        } catch (UniqueConstraintViolationException) {
            // Параллельный запрос уже создал корзину.
        }
    }

    private function lockedCart(User $user): Cart
    {
        $cart = $this->cartRepository->lockByUserId($user->id);
        if ($cart instanceof Cart) {
            return $cart;
        }

        return $this->cartRepository->createForUser($user->id);
    }

    /**
     * @param  list<array{id: int|string, quantity: int|string}>  $items
     * @return array<int, int>
     */
    private function normalize(array $items): array
    {
        $quantities = [];

        foreach ($items as $item) {
            $id = (int) ($item['id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            if ($id < 1 || $quantity < 1) {
                continue;
            }

            $quantities[$id] = ($quantities[$id] ?? 0) + $quantity;
        }

        return $quantities;
    }

    /**
     * @param  array<int, int>  $quantities
     * @return array{0: list<array{product: Product, qty: int}>, 1: list<array{id: int, reason: string}>}
     */
    private function resolve(array $quantities): array
    {
        if ($quantities === []) {
            return [[], []];
        }

        $products = $this->productRepository
            ->getByIds(array_map('intval', array_keys($quantities)))
            ->keyBy('id');

        $lines = [];
        $removed = [];

        foreach ($quantities as $productId => $quantity) {
            $productId = (int) $productId;
            $quantity = (int) $quantity;
            $product = $products->get($productId);

            if (! $product instanceof Product || ! $product->isInStock()) {
                $removed[] = [
                    'id' => $productId,
                    'reason' => 'unavailable',
                ];

                continue;
            }

            if ($product->stock !== null) {
                $quantity = min($quantity, $product->stock);
            }

            if ($quantity < 1) {
                $removed[] = [
                    'id' => $productId,
                    'reason' => 'unavailable',
                ];

                continue;
            }

            $lines[] = [
                'product' => $product,
                'qty' => $quantity,
            ];
        }

        usort(
            $lines,
            static fn (array $left, array $right): int => $left['product']->id <=> $right['product']->id,
        );

        return [$lines, $removed];
    }

    /**
     * @param  array<int, int>  $current
     * @param  list<array{product: Product, qty: int}>  $lines
     */
    private function linesChanged(array $current, array $lines): bool
    {
        $next = [];
        foreach ($lines as $line) {
            $next[$line['product']->id] = $line['qty'];
        }

        ksort($current);
        ksort($next);

        return $current !== $next;
    }

    /**
     * @param  list<array{product: Product, qty: int}>  $lines
     * @param  list<array{id: int, reason: string}>  $removed
     * @return array{updated_at: string|null, items: list<array<string, mixed>>, removed: list<array{id: int, reason: string}>}
     */
    private function present(Cart $cart, array $lines, array $removed): array
    {
        return [
            'updated_at' => $cart->updated_at?->toIso8601String(),
            'items' => CartLineResource::collection(collect($lines))->resolve(),
            'removed' => $removed,
        ];
    }
}
