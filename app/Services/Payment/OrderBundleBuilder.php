<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\OrderItem;

final readonly class OrderBundleBuilder
{
    /**
     * @return array{orderBundle: array, taxSystem: int, email: string|null}|null
     */
    public function build(Order $order): ?array
    {
        if (! $this->isEnabled()) {
            return null;
        }

        $order->loadMissing('items');

        $expectedAmount = $this->calculateExpectedAmount($order);
        $items = $this->buildCartItems($order, $expectedAmount);
        $customerDetails = $this->buildCustomerDetails($order);

        return [
            'orderBundle' => array_filter([
                'orderCreationDate' => $order->created_at->format('Y-m-d\TH:i:s'),
                'customerDetails' => $customerDetails,
                'cartItems' => ['items' => $items],
            ]),
            'taxSystem' => $this->taxSystem(),
            'email' => $order->email,
        ];
    }

    private function calculateExpectedAmount(Order $order): int
    {
        $total = $order->total_price_with_discount ?? $order->total_price;

        return $this->toMinorAmount((float) $total + (float) ($order->delivery_price ?? 0));
    }

    /**
     * @return array<int, array>
     */
    private function buildCartItems(Order $order, int $expectedAmountKopecks): array
    {
        $items = [];
        $positionId = 1;

        foreach ($order->items as $orderItem) {
            $items[] = $this->buildItem($orderItem, $positionId);
            $positionId++;
        }

        if ($order->delivery_price && $order->delivery_price > 0) {
            $items[] = $this->buildDeliveryItem($order, $positionId);
        }

        $this->adjustForRounding($items, $expectedAmountKopecks);

        return $items;
    }

    /**
     * Коррекция последней позиции чтобы сумма позиций совпала с суммой платежа.
     * Расхождение возможно из-за округлений при применении промокодов.
     *
     * @param  array<int, array>  &$items
     */
    private function adjustForRounding(array &$items, int $expectedAmountKopecks): void
    {
        if (empty($items)) {
            return;
        }

        $actualSum = 0;
        foreach ($items as $item) {
            $actualSum += $item['itemAmount'];
        }

        $diff = $expectedAmountKopecks - $actualSum;
        if ($diff === 0) {
            return;
        }

        $lastIndex = count($items) - 1;
        $items[$lastIndex]['itemAmount'] += $diff;
        $items[$lastIndex]['tax']['taxSum'] = $this->calculateTaxSum(
            (float) $items[$lastIndex]['itemAmount'] / 100
        );
    }

    private function buildItem(OrderItem $item, int $positionId): array
    {
        $priceInKopecks = $this->toMinorAmount($item->price);
        $totalInKopecks = $this->toMinorAmount($item->total);
        $taxSum = $this->calculateTaxSum($item->total);

        return [
            'positionId' => $positionId,
            'name' => mb_substr($item->name, 0, 255),
            'quantity' => [
                'value' => $item->count,
                'measure' => '0',
            ],
            'itemAmount' => $totalInKopecks,
            'itemPrice' => $priceInKopecks,
            'itemCode' => (string) $item->product_id,
            'tax' => [
                'taxType' => $this->defaultTaxType(),
                'taxSum' => $taxSum,
            ],
            'itemAttributes' => [
                'attributes' => [
                    ['name' => 'paymentMethod', 'value' => (string) $this->paymentMethod()],
                    ['name' => 'paymentObject', 'value' => (string) $this->paymentObject()],
                ],
            ],
        ];
    }

    private function buildDeliveryItem(Order $order, int $positionId): array
    {
        $deliveryPrice = (float) $order->delivery_price;
        $priceInKopecks = $this->toMinorAmount($deliveryPrice);
        $taxSum = $this->calculateTaxSum($deliveryPrice);

        return [
            'positionId' => $positionId,
            'name' => mb_substr($this->deliveryName(), 0, 255),
            'quantity' => [
                'value' => 1,
                'measure' => '0',
            ],
            'itemAmount' => $priceInKopecks,
            'itemPrice' => $priceInKopecks,
            'itemCode' => 'delivery',
            'tax' => [
                'taxType' => $this->defaultTaxType(),
                'taxSum' => $taxSum,
            ],
            'itemAttributes' => [
                'attributes' => [
                    ['name' => 'paymentMethod', 'value' => (string) $this->paymentMethod()],
                    ['name' => 'paymentObject', 'value' => (string) $this->deliveryPaymentObject()],
                ],
            ],
        ];
    }

    /**
     * @return array{email?: string, phone?: string}
     */
    private function buildCustomerDetails(Order $order): array
    {
        $details = [];

        if ($order->email) {
            $details['email'] = $order->email;
        }

        if ($order->phone) {
            $details['phone'] = preg_replace('/[^\d+]/', '', $order->phone);
        }

        return $details;
    }

    private function calculateTaxSum(float $amount): int
    {
        $taxType = $this->defaultTaxType();

        $rate = match ($taxType) {
            1 => 0.0,
            2 => 0.10,
            4 => 10.0 / 110.0,
            6 => 0.20,
            7 => 20.0 / 120.0,
            10 => 0.05,
            11 => 5.0 / 105.0,
            12 => 0.07,
            13 => 7.0 / 107.0,
            default => 0.0,
        };

        return $this->toMinorAmount($amount * $rate);
    }

    private function toMinorAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function isEnabled(): bool
    {
        return (bool) config('services.alfabank.fiscal.enabled', false);
    }

    private function taxSystem(): int
    {
        return (int) config('services.alfabank.fiscal.tax_system', 1);
    }

    private function defaultTaxType(): int
    {
        return (int) config('services.alfabank.fiscal.default_tax_type', 10);
    }

    private function paymentMethod(): int
    {
        return (int) config('services.alfabank.fiscal.payment_method', 4);
    }

    private function paymentObject(): int
    {
        return (int) config('services.alfabank.fiscal.payment_object', 1);
    }

    private function deliveryPaymentObject(): int
    {
        return (int) config('services.alfabank.fiscal.delivery_payment_object', 4);
    }

    private function deliveryName(): string
    {
        return (string) config('services.alfabank.fiscal.delivery_name', 'Доставка');
    }
}
