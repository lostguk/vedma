<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->public_id,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_url' => $this->payment_url,
            'paid_at' => $this->paid_at?->toDateTimeString(),
            'refunded_at' => $this->refunded_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
