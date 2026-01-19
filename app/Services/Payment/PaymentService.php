<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Carbon;
use RuntimeException;
use Throwable;

final readonly class PaymentService
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private OrderRepository $orderRepository,
        private AlfaBankGateway $gateway,
    ) {}

    /**
     * Создать платеж и зарегистрировать его в Альфа-Банке.
     *
     * @throws Throwable
     */
    public function createPayment(int $orderId, ?string $successUrl = null, ?string $failUrl = null): Payment
    {
        /** @var Order $order */
        $order = $this->orderRepository->findById($orderId);

        $existing = $this->paymentRepository->findLatestForOrder($order->id);
        if ($existing && in_array($existing->status, [
            Payment::STATUS_CREATED,
            Payment::STATUS_REGISTERED,
            Payment::STATUS_PENDING,
        ], true)) {
            return $existing;
        }

        if ($existing && $existing->status === Payment::STATUS_PAID) {
            throw new RuntimeException('Оплата по заказу уже завершена.');
        }

        $amount = $this->calculateAmount($order);
        $payment = $this->paymentRepository->createPayment([
            'order_id' => $order->id,
            'provider' => 'alfabank',
            'status' => Payment::STATUS_CREATED,
            'amount' => $amount,
            'currency' => (string) config('services.alfabank.currency', 'RUB'),
        ]);

        try {
            $response = $this->gateway->registerOrder($order, $payment, $successUrl, $failUrl);

            $payment = $this->paymentRepository->updatePayment($payment, [
                'external_order_id' => $response['orderId'] ?? null,
                'payment_url' => $response['formUrl'] ?? null,
                'status' => Payment::STATUS_REGISTERED,
                'payload' => $response,
            ]);
        } catch (Throwable $exception) {
            $this->paymentRepository->updatePayment($payment, [
                'status' => Payment::STATUS_FAILED,
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $order->update([
            'payment_type' => 'online',
            'status' => $order->status === 'new' ? 'payment_pending' : $order->status,
        ]);

        return $payment;
    }

    public function refreshStatus(Payment $payment): Payment
    {
        if (! $payment->external_order_id) {
            return $payment;
        }

        $response = $this->gateway->getOrderStatus($payment->external_order_id);
        $status = $this->mapStatus($response);

        $update = [
            'status' => $status,
            'payload' => $response,
        ];

        if ($status === Payment::STATUS_PAID && ! $payment->paid_at) {
            $update['paid_at'] = Carbon::now();
        }

        if ($status === Payment::STATUS_REFUNDED && ! $payment->refunded_at) {
            $update['refunded_at'] = Carbon::now();
        }

        $payment = $this->paymentRepository->updatePayment($payment, $update);
        $this->syncOrderStatus($payment);

        return $payment;
    }

    public function refund(Payment $payment, ?float $amount = null): Payment
    {
        if (! $payment->external_order_id) {
            throw new RuntimeException('Нельзя выполнить возврат без orderId платежа.');
        }

        if ($amount !== null && $amount <= 0) {
            throw new RuntimeException('Сумма возврата должна быть больше нуля.');
        }

        $refundAmount = $amount ?? $payment->amount;
        $response = $this->gateway->refund($payment->external_order_id, $this->toMinorAmount($refundAmount));

        $payment = $this->paymentRepository->updatePayment($payment, [
            'status' => Payment::STATUS_REFUNDED,
            'payload' => $response,
            'refunded_at' => Carbon::now(),
        ]);

        $this->syncOrderStatus($payment);

        return $payment;
    }

    public function handleWebhook(array $payload): ?Payment
    {
        $externalOrderId = $payload['orderId'] ?? $payload['mdOrder'] ?? null;
        if (! $externalOrderId) {
            return null;
        }

        $payment = $this->paymentRepository->findByExternalOrderId((string) $externalOrderId);
        if (! $payment) {
            return null;
        }

        $status = $this->mapStatus($payload);
        $update = [
            'status' => $status,
            'payload' => $payload,
        ];

        if ($status === Payment::STATUS_PAID && ! $payment->paid_at) {
            $update['paid_at'] = Carbon::now();
        }

        if ($status === Payment::STATUS_REFUNDED && ! $payment->refunded_at) {
            $update['refunded_at'] = Carbon::now();
        }

        $payment = $this->paymentRepository->updatePayment($payment, $update);
        $this->syncOrderStatus($payment);

        return $payment;
    }

    private function calculateAmount(Order $order): float
    {
        return $order->total_price + ($order->delivery_price ?? 0);
    }

    private function toMinorAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function mapStatus(array $payload): string
    {
        $orderStatus = $payload['orderStatus'] ?? null;
        if ($orderStatus !== null) {
            return match ((int) $orderStatus) {
                2 => Payment::STATUS_PAID,
                3, 6 => Payment::STATUS_FAILED,
                4 => Payment::STATUS_REFUNDED,
                default => Payment::STATUS_PENDING,
            };
        }

        $status = $payload['status'] ?? null;
        if (is_string($status)) {
            return match (strtolower($status)) {
                'paid', 'success', 'completed' => Payment::STATUS_PAID,
                'refunded' => Payment::STATUS_REFUNDED,
                'failed', 'declined', 'error' => Payment::STATUS_FAILED,
                default => Payment::STATUS_PENDING,
            };
        }

        return Payment::STATUS_PENDING;
    }

    private function syncOrderStatus(Payment $payment): void
    {
        $order = $payment->order;

        if ($payment->status === Payment::STATUS_PAID) {
            $order->update([
                'status' => 'paid',
                'paid_at' => $payment->paid_at ?? Carbon::now(),
            ]);
        }

        if ($payment->status === Payment::STATUS_REFUNDED) {
            $order->update([
                'status' => 'refunded',
            ]);
        }

        if ($payment->status === Payment::STATUS_FAILED) {
            $order->update([
                'status' => 'payment_failed',
            ]);
        }
    }
}
