<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Services\ProductStockService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

final readonly class PaymentService
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private OrderRepository $orderRepository,
        private TBankGateway $gateway,
        private ReceiptBuilder $receiptBuilder,
        private ProductStockService $productStockService,
    ) {}

    /**
     * Создать платеж и зарегистрировать его в Т-Банке.
     *
     * @throws Throwable
     */
    public function createPayment(int $orderId, ?string $successUrl = null, ?string $failUrl = null): Payment
    {
        /** @var Order $order */
        $order = $this->orderRepository->findById($orderId);

        if (! $order->isPayable()) {
            if ($order->status === 'cancelled' || $order->hasReleasedStock()) {
                throw new RuntimeException('Заказ отменён, оплата недоступна.');
            }

            throw new RuntimeException('Оплата по заказу уже завершена.');
        }

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
            'provider' => 'tbank',
            'status' => Payment::STATUS_CREATED,
            'amount' => $amount,
            'currency' => (string) config('services.tbank.currency', 'RUB'),
        ]);

        $publicId = (string) $payment->public_id;
        $successUrl = $this->appendPaymentPublicId($successUrl, $publicId);
        $failUrl = $this->appendPaymentPublicId($failUrl, $publicId);

        try {
            $receipt = $this->receiptBuilder->build($order);
            $response = $this->gateway->init($order, $payment, $successUrl, $failUrl, $receipt);

            $payment = $this->paymentRepository->updatePayment($payment, [
                'external_order_id' => isset($response['PaymentId']) ? (string) $response['PaymentId'] : null,
                'payment_url' => $response['PaymentURL'] ?? null,
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

        $this->assertSupportedProvider($payment);

        $response = $this->gateway->getState($payment->external_order_id);
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
        $this->assertSupportedProvider($payment);

        if (! $payment->external_order_id) {
            throw new RuntimeException('Нельзя выполнить возврат без идентификатора платежа.');
        }

        if ($amount !== null && $amount <= 0) {
            throw new RuntimeException('Сумма возврата должна быть больше нуля.');
        }

        $refundAmount = $amount ?? (float) $payment->amount;
        $response = $this->gateway->cancel(
            $payment->external_order_id,
            $amount === null ? null : $this->toMinorAmount($refundAmount),
        );

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
        if (! $this->gateway->isValidNotification($payload)) {
            throw new RuntimeException('Неверная подпись уведомления Т-Банка.');
        }

        $payment = $this->findWebhookPayment($payload);
        if (! $payment) {
            Log::warning('Уведомление Т-Банка не сопоставлено с платежом', [
                'payment_id' => $payload['PaymentId'] ?? null,
                'order_id' => $payload['OrderId'] ?? null,
            ]);

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

    /**
     * Добавляет query-параметр payment (public_id) для страниц возврата с банка.
     */
    private function appendPaymentPublicId(?string $url, string $publicId): ?string
    {
        if ($url === null || trim($url) === '') {
            return $url;
        }

        $trimmed = trim($url);
        $parts = parse_url($trimmed);
        if ($parts === false) {
            return $trimmed;
        }

        parse_str($parts['query'] ?? '', $query);
        $query['payment'] = $publicId;
        $queryString = http_build_query($query);

        $result = '';

        if (isset($parts['scheme'], $parts['host'])) {
            $result .= $parts['scheme'].'://';
            if (isset($parts['user'])) {
                $result .= $parts['user'];
                if (isset($parts['pass'])) {
                    $result .= ':'.$parts['pass'];
                }
                $result .= '@';
            }
            $result .= $parts['host'];
            if (isset($parts['port'])) {
                $result .= ':'.$parts['port'];
            }
        }

        $result .= $parts['path'] ?? '';

        if ($queryString !== '') {
            $result .= '?'.$queryString;
        }

        if (isset($parts['fragment'])) {
            $result .= '#'.$parts['fragment'];
        }

        return $result;
    }

    private function calculateAmount(Order $order): int
    {
        $totalWithDiscount = $order->total_price_with_discount ?? $order->total_price;

        return (int) round((float) $totalWithDiscount + (float) ($order->delivery_price ?? 0));
    }

    private function toMinorAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function findWebhookPayment(array $payload): ?Payment
    {
        $paymentId = $payload['PaymentId'] ?? null;
        if ($paymentId !== null && (string) $paymentId !== '') {
            $payment = $this->paymentRepository->findByExternalOrderId((string) $paymentId);
            if ($payment) {
                return $payment;
            }
        }

        $orderId = $payload['OrderId'] ?? null;
        if (is_string($orderId) && $orderId !== '') {
            return $this->paymentRepository->findByPublicId($orderId);
        }

        return null;
    }

    private function assertSupportedProvider(Payment $payment): void
    {
        if ($payment->provider === 'tbank') {
            return;
        }

        throw new RuntimeException('Платёж создан в другом шлюзе и больше не обслуживается.');
    }

    private function mapStatus(array $payload): string
    {
        $status = $payload['Status'] ?? $payload['status'] ?? null;
        if (! is_string($status)) {
            return Payment::STATUS_PENDING;
        }

        return match (strtoupper($status)) {
            'CONFIRMED', 'PARTIAL_REFUNDED' => Payment::STATUS_PAID,
            'REFUNDED' => Payment::STATUS_REFUNDED,
            'REJECTED', 'CANCELED', 'AUTH_FAIL', 'DEADLINE_EXPIRED', 'REVERSED' => Payment::STATUS_FAILED,
            'PAID', 'SUCCESS', 'COMPLETED' => Payment::STATUS_PAID,
            'FAILED', 'DECLINED', 'ERROR' => Payment::STATUS_FAILED,
            default => Payment::STATUS_PENDING,
        };
    }

    private function syncOrderStatus(Payment $payment): void
    {
        $order = $payment->order;

        if ($payment->status === Payment::STATUS_PAID) {
            if ($order->hasReleasedStock() || $order->status === 'cancelled') {
                Log::warning('Получена оплата по заказу с возвращённым складом', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,
                    'order_status' => $order->status,
                ]);

                return;
            }

            $order->update([
                'status' => 'paid',
                'paid_at' => $payment->paid_at ?? Carbon::now(),
            ]);
        }

        if ($payment->status === Payment::STATUS_REFUNDED) {
            $order->update([
                'status' => 'refunded',
            ]);
            $this->productStockService->restore($order);
        }

        if ($payment->status === Payment::STATUS_FAILED) {
            if (in_array($order->status, ['paid', 'refunded', 'cancelled'], true)) {
                return;
            }

            $order->update([
                'status' => 'payment_failed',
            ]);
        }
    }
}
