<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Payment;

final class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function createPayment(array $data): Payment
    {
        /** @var Payment $payment */
        $payment = $this->create($data);

        return $payment;
    }

    public function findLatestForOrder(int $orderId): ?Payment
    {
        return $this->model->where('order_id', $orderId)->latest('id')->first();
    }

    public function findByPublicId(string $publicId): ?Payment
    {
        return $this->model->where('public_id', $publicId)->first();
    }

    public function findByExternalOrderId(string $externalOrderId): ?Payment
    {
        return $this->model->where('external_order_id', $externalOrderId)->first();
    }

    public function updatePayment(Payment $payment, array $data): Payment
    {
        $payment->update($data);

        return $payment->refresh();
    }
}
