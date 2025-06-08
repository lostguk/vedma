<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PromoCode;

final class PromoCodeRepository
{
    /**
     * Найти активный промокод по коду
     */
    public function findActiveByCode(string $code): ?PromoCode
    {
        $now = now();

        return PromoCode::where('code', $code)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();
    }
}
