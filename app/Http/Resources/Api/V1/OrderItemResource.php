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
        $count = (int) $this->count;
        $priceWithDiscount = (int) round((float) $this->price);
        $priceWithoutDiscount = $this->priceWithoutDiscount($priceWithDiscount);
        $totalWithDiscount = (int) round((float) ($this->total ?? $priceWithDiscount * $count));

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'price' => $priceWithDiscount,
            'price_without_discount' => $priceWithoutDiscount,
            'count' => $count,
            'total' => $totalWithDiscount,
            'total_without_discount' => $priceWithoutDiscount * $count,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }

    private function priceWithoutDiscount(int $priceWithDiscount): int
    {
        if ($this->price_without_discount !== null) {
            return (int) round((float) $this->price_without_discount);
        }

        $catalogPrice = $this->relationLoaded('product') && $this->product
            ? (int) round((float) $this->product->price)
            : $priceWithDiscount;

        return max($priceWithDiscount, $catalogPrice);
    }
}
