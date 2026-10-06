<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\CartSyncRequest;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class CartController extends ApiController
{
    /**
     * @group Корзина
     *
     * Корзина текущего пользователя. Цены и остатки берутся из каталога.
     *
     * @authenticated
     */
    public function show(CartService $cartService): JsonResponse
    {
        return $this->successResponse($cartService->get($this->currentUser()));
    }

    /**
     * @group Корзина
     *
     * Полностью заменить корзину. Пустой список очищает её.
     * Товары, которых нет или которых нет в наличии, возвращаются в `removed` и не сохраняются.
     *
     * @authenticated
     */
    public function update(CartSyncRequest $request, CartService $cartService): JsonResponse
    {
        return $this->successResponse(
            $cartService->replace($this->currentUser(), $request->validated('items')),
        );
    }

    /**
     * @group Корзина
     *
     * Прибавить позиции к корзине пользователя. Одинаковые товары складываются и обрезаются по остатку.
     *
     * @authenticated
     */
    public function merge(CartSyncRequest $request, CartService $cartService): JsonResponse
    {
        return $this->successResponse(
            $cartService->merge($this->currentUser(), $request->validated('items')),
        );
    }

    /**
     * @group Корзина
     *
     * Очистить корзину текущего пользователя.
     *
     * @authenticated
     */
    public function destroy(CartService $cartService): JsonResponse
    {
        $user = $this->currentUser();
        $cartService->clear($user);

        return $this->successResponse($cartService->get($user));
    }

    private function currentUser(): User
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(401, 'Unauthorized');
        }

        return $user;
    }
}
