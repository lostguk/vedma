<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\V1\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'product_id' => $this->product_id,
            'name' => $this->name,
            'price' => $this->price,
            'count' => $this->count,
            'total' => $this->total,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
