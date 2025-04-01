<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @mixin Product
 */
#[OA\Schema(
    schema: 'ProductResource',
    description: 'Ресурс продукта',
    required: ['id', 'name', 'slug', 'price', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Смартфон iPhone 13'),
        new OA\Property(property: 'slug', type: 'string', example: 'iphone-13'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Мощный смартфон с отличной камерой'),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 79999.99),
        new OA\Property(
            property: 'dimensions',
            type: 'object',
            properties: [
                new OA\Property(property: 'width', type: 'number', format: 'float', example: 71.5),
                new OA\Property(property: 'height', type: 'number', format: 'float', example: 146.7),
                new OA\Property(property: 'depth', type: 'number', format: 'float', example: 7.65),
                new OA\Property(property: 'weight', type: 'number', format: 'float', example: 174),
            ]
        ),
        new OA\Property(
            property: 'categories',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Category')
        ),
        new OA\Property(
            property: 'related',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ProductResource')
        ),
        new OA\Property(
            property: 'images_urls',
            type: 'array',
            items: new OA\Items(type: 'string', example: 'https://example.com/images/iphone-13.jpg')
        ),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
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
            'dimensions' => [
                'width' => $this->width,
                'height' => $this->height,
                'depth' => $this->depth,
                'weight' => $this->weight,
            ],
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'related' => ProductResource::collection($this->whenLoaded('related')),
            'images_urls' => $this->getMedia()->map(fn ($media) => $media->getUrl()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
