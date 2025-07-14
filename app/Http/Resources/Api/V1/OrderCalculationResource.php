<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCalculationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'slug' => $this['slug'],
            'description' => $this['description'],
            'price' => $this['price'],
            'old_price' => $this['old_price'],
            'weight' => $this['weight'],
            'width' => $this['width'],
            'height' => $this['height'],
            'length' => $this['length'],
            'is_new' => $this['is_new'],
            'is_bestseller' => $this['is_bestseller'],
            'sort_order' => $this['sort_order'],
            'images_urls' => $this['images_urls'] ?? [],
            'count' => $this['count'],
            'summery' => $this['summery'],
            'summery_old' => $this['summery_old'],
            'discounted' => $this['discounted'],
        ];
    }
}
