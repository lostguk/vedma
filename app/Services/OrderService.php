<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PromoCodeRepository;
use App\Services\Auth\RegistrationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository,
        private PromoCodeRepository $promoCodeRepository,
        private RegistrationService $registrationService,
        private OrderCalculationService $orderCalculationService,
    ) {}

    /**
     * Оформить заказ
     *
     * @throws Throwable
     */
    public function createOrder(array $data): Order
    {

        return DB::transaction(function () use ($data) {
            // 1. Определяем пользователя
            $user = Auth::guard('sanctum')->user();

            if (! $user && ($data['register'] ?? false)) {
                $user = $this->registrationService->register([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'middle_name' => $data['middle_name'] ?? '',
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'password_confirmation' => $data['password'],
                    'phone' => $data['phone'] ?? null,
                    'address' => $data['address'] ?? null,
                ]);
            }

            // 2. Промокод
            $promo = null;
            if (! empty($data['promo_code'])) {
                $promo = $this->promoCodeRepository->findActiveByCode($data['promo_code']);
            }

            // 3. Расчет заказа
            $productIds = collect($data['items'])->pluck('id')->all();
            $products = $this->productRepository->getByIds($productIds)->load('categories');
            $calculated = $this->orderCalculationService->calculate($products, $data['items'], $promo);
            $calculatedItems = $calculated['items'] ?? [];
            $totalWithoutDiscount = (int) round((float) ($calculated['total_without_discount'] ?? 0));
            $totalWithDiscount = (int) round((float) ($calculated['total_with_discount'] ?? 0));
            $deliveryPrice = isset($data['delivery_price'])
                ? (int) round((float) $data['delivery_price'])
                : null;

            // 4. Создание заказа
            $orderData = [
                'user_id' => $user?->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'promo_code_id' => $promo?->id,
                'total_price' => $totalWithDiscount,
                'total_price_without_discount' => $totalWithoutDiscount,
                'total_price_with_discount' => $totalWithDiscount,
                'status' => 'new',
                'payment_type' => null,
                'paid_at' => null,
                'comment' => $data['comment'] ?? null,
                'delivery_type' => $data['delivery_type'] ?? null,
                'delivery_price' => $deliveryPrice,
                'delivery_status' => null,
                'delivery_data' => null,
            ];
            $order = $this->orderRepository->createOrder($orderData);

            // 5. Создание позиций заказа
            $items = [];
            foreach ($calculatedItems as $item) {
                $items[] = [
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'price' => (int) round((float) $item['price']),
                    'count' => $item['count'],
                    'total' => (int) round((float) $item['summery']),
                ];
            }
            $this->orderRepository->createOrderItems($order, $items);

            // 6. Имитация оплаты (можно доработать позже)
            // TODO: реализовать оплату

            return $order;
        });
    }

    /**
     * Получить заказы пользователя
     */
    public function getUserOrders(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getByUserId($userId, $perPage);
    }
}
