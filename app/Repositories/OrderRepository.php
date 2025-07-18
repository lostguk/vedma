<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * Создать заказ
     */
    public function createOrder(array $data): Order
    {
        /** @var Order $order */
        $order = $this->create($data);

        return $order;
    }

    /**
     * Создать позиции заказа
     *
     * @return Collection<OrderItem>
     */
    public function createOrderItems(Order $order, array $items): Collection
    {
        $created = [];
        foreach ($items as $item) {
            $created[] = $order->items()->create($item);
        }

        return new Collection($created);
    }

    /**
     * Получить заказы пользователя
     */
    public function getByUserId(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->with('items', 'items.product')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
