<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final readonly class TBankGateway
{
    /**
     * @param  array<string, mixed>|null  $receipt
     * @return array<string, mixed>
     */
    public function init(
        Order $order,
        Payment $payment,
        ?string $successUrl = null,
        ?string $failUrl = null,
        ?array $receipt = null,
    ): array {
        $payload = [
            'TerminalKey' => $this->terminalKey(),
            'Amount' => $this->toMinorAmount((float) $payment->amount),
            'OrderId' => (string) $payment->public_id,
            'Description' => 'Оплата заказа №'.$order->id,
            'PayType' => 'O',
            'Language' => 'ru',
            'SuccessURL' => $successUrl ?? $this->defaultReturnUrl(),
            'FailURL' => $failUrl ?? $this->defaultFailUrl(),
            'NotificationURL' => $this->notificationUrl(),
        ];

        $data = $this->customerData($order);
        if ($data !== []) {
            $payload['DATA'] = $data;
        }

        if ($receipt !== null) {
            $payload['Receipt'] = $receipt;
        }

        $payload['Token'] = $this->token($payload);

        return $this->parseResponse($this->post('/Init', $payload));
    }

    /**
     * @return array<string, mixed>
     */
    public function getState(string $paymentId): array
    {
        $payload = [
            'TerminalKey' => $this->terminalKey(),
            'PaymentId' => $paymentId,
        ];
        $payload['Token'] = $this->token($payload);

        return $this->parseResponse($this->post('/GetState', $payload));
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $paymentId, ?int $amount = null): array
    {
        $payload = [
            'TerminalKey' => $this->terminalKey(),
            'PaymentId' => $paymentId,
        ];

        if ($amount !== null) {
            $payload['Amount'] = $amount;
        }

        $payload['Token'] = $this->token($payload);

        return $this->parseResponse($this->post('/Cancel', $payload));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function isValidNotification(array $payload): bool
    {
        $token = $payload['Token'] ?? null;
        if (! is_string($token) || $token === '') {
            return false;
        }

        return hash_equals($this->token($payload), $token);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function post(string $path, array $payload): Response
    {
        return Http::asJson()
            ->withOptions(['verify' => (bool) config('services.tbank.verify_ssl', true)])
            ->timeout(15)
            ->post($this->baseUrl().$path, $payload);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function token(array $params): string
    {
        $flat = [];

        foreach ($params as $key => $value) {
            if ($key === 'Token' || is_array($value) || $value === null) {
                continue;
            }

            $flat[$key] = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
        }

        $flat['Password'] = $this->password();
        ksort($flat, SORT_STRING);

        return hash('sha256', implode('', $flat));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function parseResponse(Response $response): array
    {
        if ($response->failed()) {
            throw new RuntimeException('Ошибка при обращении к платежному шлюзу.');
        }

        $data = $response->json() ?? [];

        $errorCode = (string) ($data['ErrorCode'] ?? '0');
        if (($data['Success'] ?? true) === false || ($errorCode !== '0' && $errorCode !== '')) {
            $message = $data['Message'] ?? $data['Details'] ?? 'Платежный шлюз вернул ошибку.';
            throw new RuntimeException((string) $message);
        }

        return $data;
    }

    /**
     * @return array<string, string>
     */
    private function customerData(Order $order): array
    {
        $data = [];

        if ($order->email) {
            $data['Email'] = (string) $order->email;
        }

        $phone = $this->normalizePhone($order->phone);
        if ($phone !== null) {
            $data['Phone'] = $phone;
        }

        return $data;
    }

    private function normalizePhone(mixed $phone): ?string
    {
        if (! is_string($phone) || trim($phone) === '') {
            return null;
        }

        $normalized = preg_replace('/[^\d+]/', '', $phone);
        if (! is_string($normalized) || $normalized === '') {
            return null;
        }

        return $normalized;
    }

    private function toMinorAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('services.tbank.base_url'), '/');
    }

    private function terminalKey(): string
    {
        return (string) config('services.tbank.terminal_key');
    }

    private function password(): string
    {
        return (string) config('services.tbank.password');
    }

    private function defaultReturnUrl(): string
    {
        return (string) config('services.tbank.return_url');
    }

    private function defaultFailUrl(): string
    {
        return (string) config('services.tbank.fail_url');
    }

    private function notificationUrl(): string
    {
        $configured = trim((string) config('services.tbank.notification_url', ''));
        if ($configured !== '') {
            return $configured;
        }

        return route('api.v1.payments.tbank.webhook');
    }
}
