<?php

namespace App\Http\Resources\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Category $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->getFirstMediaUrl(Category::ICON_COLLECTION) ?: null,
            'parent_id' => $this->parent_id,
            'is_visible' => $this->is_visible,
            'exclude_from_shipping' => $this->exclude_from_shipping,
            'children' => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
