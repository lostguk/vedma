<?php

namespace App\Http\Resources\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @mixin Category
 */
#[OA\Schema(
    schema: 'CategoryResource',
    description: 'Ресурс категории',
    required: ['id', 'name', 'slug'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Свечи'),
        new OA\Property(property: 'slug', type: 'string', example: 'svechi'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'icon', type: 'string', nullable: true),
        new OA\Property(property: 'parent_id', type: 'integer', nullable: true),
        new OA\Property(property: 'sort_order', type: 'integer', example: 1),
        new OA\Property(property: 'is_visible', type: 'boolean', example: true),
        new OA\Property(
            property: 'children',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/CategoryResource'),
        ),
    ],
)]
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
