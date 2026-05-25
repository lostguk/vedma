<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

final readonly class AlfaBankGateway
{
    /**
     * @param  array{orderBundle?: array, taxSystem?: int, email?: string|null}|null  $fiscalData
     */
    public function registerOrder(
        Order $order,
        Payment $payment,
        ?string $returnUrl = null,
        ?string $failUrl = null,
        ?array $fiscalData = null,
    ): array {
        $payload = [
            'userName' => $this->username(),
            'password' => $this->password(),
            'orderNumber' => $this->buildOrderNumber($order, $payment),
            'amount' => $this->toMinorAmount($payment->amount),
            'returnUrl' => $returnUrl ?? $this->defaultReturnUrl(),
            'failUrl' => $failUrl ?? $this->defaultFailUrl(),
            'language' => $this->language(),
        ];
        $currency = $this->currency();
        if ($currency !== null) {
            $payload['currency'] = $currency;
        }

        if ($fiscalData !== null) {
            if (isset($fiscalData['taxSystem'])) {
                $payload['taxSystem'] = $fiscalData['taxSystem'];
            }
            if (! empty($fiscalData['email'])) {
                $payload['email'] = $fiscalData['email'];
            }
            if (isset($fiscalData['orderBundle'])) {
                $payload['orderBundle'] = json_encode($fiscalData['orderBundle'], JSON_UNESCAPED_UNICODE);
            }
        }

        $response = $this->post('/payment/rest/register.do', $payload);

        return $this->parseResponse($response);
    }

    public function getOrderStatus(string $externalOrderId): array
    {
        $payload = [
            'userName' => $this->username(),
            'password' => $this->password(),
            'orderId' => $externalOrderId,
        ];

        $response = $this->post('/payment/rest/getOrderStatusExtended.do', $payload);

        return $this->parseResponse($response);
    }

    public function refund(string $externalOrderId, int $amount): array
    {
        $payload = [
            'userName' => $this->username(),
            'password' => $this->password(),
            'orderId' => $externalOrderId,
            'amount' => $amount,
        ];

        $response = $this->post('/payment/rest/refund.do', $payload);

        return $this->parseResponse($response);
    }

    private function post(string $path, array $payload): Response
    {
        return Http::asForm()
            ->timeout(15)
            ->post($this->baseUrl().$path, $payload);
    }

    private function parseResponse(Response $response): array
    {
        if ($response->failed()) {
            throw new RuntimeException('Ошибка при обращении к платежному шлюзу.');
        }

        $data = $response->json() ?? [];

        if (! empty($data['errorCode']) && (int) $data['errorCode'] !== 0) {
            $message = $data['errorMessage'] ?? 'Платежный шлюз вернул ошибку.';
            throw new RuntimeException((string) $message);
        }

        return $data;
    }

    private function buildOrderNumber(Order $order, Payment $payment): string
    {
        $base = Str::replace('-', '', $payment->public_id ?? Str::uuid()->toString());

        return Str::limit($base, 32, '');
    }

    private function toMinorAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('services.alfabank.base_url'), '/');
    }

    private function username(): string
    {
        return (string) config('services.alfabank.username');
    }

    private function password(): string
    {
        return (string) config('services.alfabank.password');
    }

    private function defaultReturnUrl(): string
    {
        return (string) config('services.alfabank.return_url');
    }

    private function defaultFailUrl(): string
    {
        return (string) config('services.alfabank.fail_url');
    }

    private function language(): string
    {
        return (string) config('services.alfabank.language', 'ru');
    }

    private function currency(): ?string
    {
        $currency = trim((string) config('services.alfabank.currency', ''));
        if ($currency === '') {
            return null;
        }

        $normalized = strtoupper($currency);
        if ($normalized === 'RUB' || $normalized === '643') {
            return null;
        }

        return match ($normalized) {
            'RUB' => '643',
            'USD' => '840',
            'EUR' => '978',
            default => $currency,
        };
    }
}
