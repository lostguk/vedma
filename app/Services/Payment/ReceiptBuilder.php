<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\OrderItem;

final readonly class ReceiptBuilder
{
    /**
     * Чек Т-Банка (Receipt в методе Init).
     *
     * @return array<string, mixed>|null
     */
    public function build(Order $order): ?array
    {
        if (! $this->isEnabled()) {
            return null;
        }

        $order->loadMissing('items');

        $expectedAmount = $this->calculateExpectedAmount($order);
        $items = $this->buildItems($order, $expectedAmount);

        $receipt = [
            'Taxation' => $this->taxation(),
            'Items' => $items,
        ];

        if ($order->email) {
            $receipt['Email'] = (string) $order->email;
        }

        $phone = $this->normalizePhone($order->phone);
        if ($phone !== null) {
            $receipt['Phone'] = $phone;
        }

        return $receipt;
    }

    private function calculateExpectedAmount(Order $order): int
    {
        $total = $order->total_price_with_discount ?? $order->total_price;

        return $this->toMinorAmount((float) $total + (float) ($order->delivery_price ?? 0));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildItems(Order $order, int $expectedAmountKopecks): array
    {
        $items = [];

        foreach ($order->items as $orderItem) {
            $items[] = $this->buildItem($orderItem);
        }

        if ($order->delivery_price && $order->delivery_price > 0) {
            $items[] = $this->buildDeliveryItem($order);
        }

        $this->adjustForRounding($items, $expectedAmountKopecks);

        return $items;
    }

    /**
     * Сумма позиций чека должна совпасть с суммой платежа.
     * У Т-Банка Amount позиции равен Price * Quantity.
     *
     * @param  list<array<string, mixed>>  $items
     */
    private function adjustForRounding(array &$items, int $expectedAmountKopecks): void
    {
        if ($items === []) {
            return;
        }

        $actualSum = 0;
        foreach ($items as $item) {
            $actualSum += (int) $item['Amount'];
        }

        $diff = $expectedAmountKopecks - $actualSum;
        if ($diff === 0) {
            return;
        }

        $lastIndex = count($items) - 1;
        $items[$lastIndex]['Amount'] = (int) $items[$lastIndex]['Amount'] + $diff;
        $this->syncPrice($items[$lastIndex]);
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function syncPrice(array &$item): void
    {
        $quantity = (int) $item['Quantity'];
        $amount = (int) $item['Amount'];

        if ($quantity > 1 && $amount % $quantity === 0) {
            $item['Price'] = intdiv($amount, $quantity);

            return;
        }

        $item['Quantity'] = 1;
        $item['Price'] = $amount;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildItem(OrderItem $item): array
    {
        $priceInKopecks = $this->toMinorAmount((float) $item->price);
        $totalInKopecks = $this->toMinorAmount((float) $item->total);

        return [
            'Name' => mb_substr((string) $item->name, 0, 128),
            'Price' => $priceInKopecks,
            'Quantity' => (int) $item->count,
            'Amount' => $totalInKopecks,
            'Tax' => $this->tax(),
            'PaymentMethod' => $this->paymentMethod(),
            'PaymentObject' => $this->paymentObject(),
            'MeasurementUnit' => 'шт',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildDeliveryItem(Order $order): array
    {
        $priceInKopecks = $this->toMinorAmount((float) $order->delivery_price);

        return [
            'Name' => mb_substr($this->deliveryName(), 0, 128),
            'Price' => $priceInKopecks,
            'Quantity' => 1,
            'Amount' => $priceInKopecks,
            'Tax' => $this->tax(),
            'PaymentMethod' => $this->paymentMethod(),
            'PaymentObject' => $this->deliveryPaymentObject(),
            'MeasurementUnit' => 'шт',
        ];
    }

    private function normalizePhone(mixed $phone): ?string
    {
        if (! is_string($phone) || trim($phone) === '') {
            return null;
        }

        $normalized = preg_replace('/[^\d+]/', '', $phone);

        return is_string($normalized) && $normalized !== '' ? $normalized : null;
    }

    private function toMinorAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function isEnabled(): bool
    {
        return (bool) config('services.tbank.fiscal.enabled', false);
    }

    private function taxation(): string
    {
        return (string) config('services.tbank.fiscal.taxation', 'usn_income');
    }

    private function tax(): string
    {
        return (string) config('services.tbank.fiscal.tax', 'vat5');
    }

    private function paymentMethod(): string
    {
        return (string) config('services.tbank.fiscal.payment_method', 'full_payment');
    }

    private function paymentObject(): string
    {
        return (string) config('services.tbank.fiscal.payment_object', 'commodity');
    }

    private function deliveryPaymentObject(): string
    {
        return (string) config('services.tbank.fiscal.delivery_payment_object', 'service');
    }

    private function deliveryName(): string
    {
        return (string) config('services.tbank.fiscal.delivery_name', 'Доставка');
    }
}
