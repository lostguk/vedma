<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\OrderCalculateRequest;
use App\Http\Requests\Api\V1\OrderStoreRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Repositories\ProductRepository;
use App\Repositories\PromoCodeRepository;
use App\Services\OrderCalculationService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

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

    /**
     * @group Оформление заказа
     *
     * Оформление заказа (создание)
     *
     * @response 201 scenario="Успешное оформление" {"status":"success","message":"Order created"}
     */
    public function store(OrderStoreRequest $request, OrderService $orderService): JsonResponse
    {
        $order = $orderService->createOrder($request->validated());
        $order->load('items');

        return $this->successResponse(new OrderResource($order), 'Заказ успешно создан', 201);
    }

    /**
     * @group Заказы пользователя
     *
     * Получить список заказов текущего пользователя
     *
     * @authenticated
     *
     * @queryParam page int Номер страницы. Пример: 2
     * @queryParam per_page int Количество заказов на страницу. Пример: 15
     *
     * @response 200 scenario="Успешно" {
     *   "status": "success",
     *   "message": "Success",
     *   "data": {
     *     "current_page": 1,
     *     "data": [
     *       {"id": 1, "user_id": 2, ...}
     *     ],
     *     ...
     *   }
     * }
     */
    public function index(Request $request, OrderService $orderService): OrderResource|AnonymousResourceCollection
    {
        $user = Auth::user();
        $perPage = (int) $request->query('per_page', '15');
        $orders = $orderService->getUserOrders($user->id, $perPage);

        return OrderResource::collection($orders);
    }
}
