<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\OrderCalculateRequest;
use App\Repositories\ProductRepository;
use App\Repositories\PromoCodeRepository;
use App\Services\OrderCalculationService;
use Illuminate\Http\JsonResponse;

final class OrderController extends ApiController
{
    /**
     * @group Оформление заказа
     *
     * Расчет стоимости заказа с учетом промокода.
     *
     * @bodyParam items array[] Список товаров для расчета. Пример: [{"id":1,"count":3}]
     * @bodyParam items[].id int ID товара. Пример: 1
     * @bodyParam items[].count int Количество товара. Пример: 3
     * @bodyParam promo_code string Промокод (опционально). Пример: PROMO10
     *
     * @response 200 scenario="Успешный расчет" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Товар 1",
     *       "price": 100,
     *       "old_price": 120,
     *       "count": 2,
     *       "summery": 200,
     *       "summery_old": 240,
     *       "discounted": true
     *     }
     *   ],
     *   "status": "success",
     *   "message": "Success"
     * }
     */
    public function calculate(
        OrderCalculateRequest $request,
        ProductRepository $productRepository,
        PromoCodeRepository $promoCodeRepository,
        OrderCalculationService $orderCalculationService
    ): JsonResponse {
        $items = $request->input('items', []);
        $promoCode = null;

        if ($code = $request->input('promo_code')) {
            $promoCode = $promoCodeRepository->findActiveByCode($code);
        }

        $productIds = collect($items)->pluck('id')->all();
        $products = $productRepository->getByIds($productIds)->load('categories');
        $result = $orderCalculationService->calculate($products, $items, $promoCode);

        return $this->successResponse($result);
    }
}
