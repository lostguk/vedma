<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\OrderCalculateRequest;
use App\Http\Requests\Api\V1\OrderStoreRequest;
use App\Http\Resources\Api\V1\OrderCalculationResource;
use App\Http\Resources\Api\V1\OrderResource;
use App\Repositories\ProductRepository;
use App\Repositories\PromoCodeRepository;
use App\Services\OrderCalculationService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class OrderController extends ApiController
{
    /**
     * @group Оформление заказа
     *
     * Расчет стоимости заказа с учетом промокода.
     *
     * @bodyParam items array[] Список товаров для расчета. Пример: [{"id":1,"count":3}]
     * @bodyParam items[].id int ID товара. Example: 1
     * @bodyParam items[].count int Количество товара. Example: 3
     * @bodyParam promo_code string Промокод (опционально). Example: PROMO10
     *
     * @response 200 scenario="Успешный расчет" {
     *   "data": {
     *     "items": [
     *       {
     *         "id": 1,
     *         "name": "Товар 1",
     *         "price": 100,
     *         "old_price": 120,
     *         "count": 2,
     *         "summery": 200,
     *         "summery_old": 240,
     *         "discounted": true
     *       }
     *     ],
     *     "total_without_discount": 240,
     *     "total_with_discount": 200,
     *     "promo_code_status": "applied"
     *   },
     *   "status": "success",
     *   "message": "Success"
     * }
     *
     * @responseField items Массив товаров с рассчитанной стоимостью
     * @responseField items[].id ID товара
     * @responseField items[].name Название товара
     * @responseField items[].count Количество товара
     * @responseField items[].summery Итоговая стоимость товара (с учетом промокода)
     * @responseField items[].summery_old Итоговая стоимость товара без промокода
     * @responseField items[].discounted Применен ли промокод к товару
     * @responseField total_without_discount Общая сумма заказа без промокода
     * @responseField total_with_discount Общая сумма заказа с промокодом
     * @responseField promo_code_status Статус промокода: "not_exists" (промокод не существует), "not_applied" (промокод существует, но не применился к товарам), "applied" (промокод применился)
     */
    public function calculate(
        OrderCalculateRequest $request,
        ProductRepository $productRepository,
        PromoCodeRepository $promoCodeRepository,
        OrderCalculationService $orderCalculationService
    ): JsonResponse {
        $items = $request->input('items', []);
        $promoCodeInput = $request->input('promo_code');
        $promoCode = null;

        if ($promoCodeInput) {
            $promoCode = $promoCodeRepository->findActiveByCode($promoCodeInput);
        }

        $productIds = collect($items)->pluck('id')->all();
        $products = $productRepository->getByIds($productIds)->load('categories');
        $result = $orderCalculationService->calculate($products, $items, $promoCode, $promoCodeInput);

        // Преобразуем items в ресурсы
        $result['items'] = OrderCalculationResource::collection($result['items'])->resolve();

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

        return $this->successResponse(new OrderResource(resource: $order), 'Заказ успешно создан', 201);
    }

    /**
     * @group Заказы пользователя
     *
     * Получить список заказов текущего пользователя
     *
     * @authenticated
     *
     * @queryParam page int Номер страницы. Example: 1
     * @queryParam per_page int Количество заказов на страницу. Example: 15
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
    public function index(Request $request, OrderService $orderService): JsonResponse
    {
        $user = Auth::user();
        $perPage = (int) $request->query('per_page', '15');
        $orders = $orderService->getUserOrders($user->id, $perPage);

        $collection = OrderResource::collection($orders);

        return $this->successPaginatedResponse($collection);
    }
}
