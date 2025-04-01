<?php

declare(strict_types=1);

namespace App\OpenApi\Schemas\Product;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductResponse',
    description: 'Ответ с данными продукта',
    required: ['data'],
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/Product'
        ),
    ]
)]
class ProductResponse {}
