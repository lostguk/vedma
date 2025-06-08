<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'region' => $this->region,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            'promo_code' => $this->promoCode?->code,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
