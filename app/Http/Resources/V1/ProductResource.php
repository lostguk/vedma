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
            'breadcrumbs' => $this->when(
                $request->routeIs('api.v1.products.show'),
                fn () => $this->getBreadcrumbs()
            ),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'related' => ProductResource::collection($this->whenLoaded('related')),
            'images_urls' => $this->getMedia(Product::IMAGES_COLLECTION)
                ->map(fn ($media) => $media->getUrl())
                ->all(),
            'image_url' => $this->getFirstMediaUrl(Product::IMAGES_COLLECTION),
            'preview_url' => $this->getFirstMediaUrl(Product::IMAGES_COLLECTION, 'preview'),
            'thumb_url' => $this->getFirstMediaUrl(Product::IMAGES_COLLECTION, 'preview'),
            'thumb_small_url' => $this->getFirstMediaUrl(Product::IMAGES_COLLECTION, 'thumb'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getBreadcrumbs(): array
    {
        $breadcrumbs = [
            [
                'name' => 'Главная',
                'slug' => '/',
                'type' => 'home',
            ],
        ];

        /** @var \App\Models\Category|null $category */
        $categories = $this->categories;

        // Find the "deepest" category (one that is not a parent of any other assigned category)
        $category = $categories->first(function ($cat) use ($categories) {
            return ! $categories->contains('parent_id', $cat->id);
        });

        $category = $category ?? $categories->first();

        if ($category) {
            $categoryChain = collect();
            $current = $category;

            // Prevent infinite loop
            $depth = 0;
            while ($current && $depth < 10) {
                $categoryChain->prepend([
                    'name' => $current->name,
                    'slug' => $current->slug,
                    'type' => 'category',
                ]);
                $current = $current->parent;
                $depth++;
            }
            $breadcrumbs = array_merge($breadcrumbs, $categoryChain->toArray());
        }

        $breadcrumbs[] = [
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => 'product',
        ];

        return $breadcrumbs;
    }
}
