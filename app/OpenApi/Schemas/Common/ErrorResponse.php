<?php

declare(strict_types=1);

namespace App\OpenApi\Schemas\Common;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ErrorResponse',
    description: 'Ответ с ошибкой',
    required: ['message'],
    properties: [
        new OA\Property(
            property: 'message',
            type: 'string',
            example: 'Запрашиваемый ресурс не найден'
        ),
    ]
)]
class ErrorResponse {}
