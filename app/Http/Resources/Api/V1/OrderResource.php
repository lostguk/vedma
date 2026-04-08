<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Services\OrderCalculationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $calculation = $this->calculateTotals();
        $totalWithoutDiscount = (int) round((float) ($this->total_price_without_discount ?? $calculation['total_without_discount']));
        $totalWithDiscount = (int) round((float) ($this->total_price_with_discount ?? $calculation['total_with_discount']));

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'promo_code' => $this->promoCode?->code,
            'total_price' => $totalWithDiscount,
            'total_without_discount' => $totalWithoutDiscount,
            'total_with_discount' => $totalWithDiscount,
            'promo_code_status' => $calculation['promo_code_status'],
            'delivery_type' => $this->delivery_type,
            'delivery_price' => $this->delivery_price,
            'status_code' => $this->status,
            'status' => $this->statusLabel($this->status),
            'created_at' => $this->created_at?->toDateTimeString(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }

    /**
     * @return array{total_without_discount: int, total_with_discount: int, promo_code_status: string}
     */
    private function calculateTotals(): array
    {
        $items = $this->items instanceof Collection ? $this->items : collect();

        if ($items->isEmpty()) {
            return [
                'total_without_discount' => (int) round((float) $this->total_price),
                'total_with_discount' => (int) round((float) $this->total_price),
                'promo_code_status' => 'not_sent',
            ];
        }

        $products = $items->map->product->filter();
        $products->loadMissing('categories');

        $calculationItems = $items->map(static fn ($item): array => [
            'id' => $item->product_id,
            'count' => $item->count,
        ])->values()->all();

        $calculation = app(OrderCalculationService::class)->calculate(
            $products,
            $calculationItems,
            $this->promoCode,
            $this->promoCode?->code,
        );

        return [
            'total_without_discount' => (int) round((float) $calculation['total_without_discount']),
            'total_with_discount' => (int) round((float) $calculation['total_with_discount']),
            'promo_code_status' => $calculation['promo_code_status'],
        ];
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'new' => 'Новый',
            'payment_pending' => 'Ожидает оплату',
            'payment_failed' => 'Ошибка оплаты',
            'paid' => 'Оплачен',
            'refunded' => 'Возврат',
            'cancelled' => 'Отменён',
            default => $status ?? '',
        };
    }
}
