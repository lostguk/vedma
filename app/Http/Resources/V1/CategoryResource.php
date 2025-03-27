<?php

namespace App\Http\Resources\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'icon' => $this->getFirstMediaUrl('icon'),
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'is_visible' => $this->is_visible,
            'children' => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
