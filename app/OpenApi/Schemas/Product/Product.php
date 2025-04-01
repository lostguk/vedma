<?php

declare(strict_types=1);

namespace App\OpenApi\Schemas\Product;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Product',
    description: 'Модель продукта',
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
            items: new OA\Items(ref: '#/components/schemas/Product')
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
class Product {}
