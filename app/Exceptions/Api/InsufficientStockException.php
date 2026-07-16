<?php

declare(strict_types=1);

namespace App\Exceptions\Api;

use RuntimeException;

final class InsufficientStockException extends RuntimeException
{
    public function __construct(string $productName, int $available)
    {
        parent::__construct("Недостаточно товара «{$productName}» на складе. Доступно: {$available} шт.");
    }
}
