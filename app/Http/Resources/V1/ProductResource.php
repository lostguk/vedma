<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Product $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'dimensions' => $this->dimensions,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'related' => ProductResource::collection($this->whenLoaded('related')),
            'images_urls' => $this->getFirstMediaUrl('images'),
            'thumb_url' => $this->getFirstMediaUrl('images', 'thumb'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
