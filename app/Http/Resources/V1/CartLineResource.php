<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{product: Product, qty: int} $resource
 */
class CartLineResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Product $product */
        $product = $this->resource['product'];

        return [
            'id' => $product->id,
            'qty' => $this->resource['qty'],
            'name' => $product->name,
            'price' => $product->price,
            'old_price' => $product->old_price,
            'image' => $this->imageUrl($product),
            'slug' => $product->slug,
            'stock' => $product->stock,
            'in_stock' => $product->isInStock(),
        ];
    }

    private function imageUrl(Product $product): string
    {
        $thumb = $product->getFirstMediaUrl(Product::IMAGES_COLLECTION, 'thumb');
        if ($thumb !== '') {
            return $thumb;
        }

        $preview = $product->getFirstMediaUrl(Product::IMAGES_COLLECTION, 'preview');
        if ($preview !== '') {
            return $preview;
        }

        return $product->getFirstMediaUrl(Product::IMAGES_COLLECTION);
    }
}
