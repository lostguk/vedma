<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Category;
use App\Services\HomePageContentService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
final class HomePageCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Category $this */
        // Получаем товары для этой категории (включая дочерние категории)
        $service = app(HomePageContentService::class);
        $products = $service->getProductsForCategory($this->resource);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'is_visible' => $this->is_visible,
            'products' => ProductResource::collection($products),
        ];
    }
}
