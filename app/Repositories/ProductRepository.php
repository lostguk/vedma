<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;

final class ProductRepository
{
    /**
     * Получить товары по массиву id
     *
     * @param  array<int>  $ids
     * @return \Illuminate\Database\Eloquent\Collection<int, Product>
     */
    public function getByIds(array $ids)
    {
        return Product::query()->whereIn('id', $ids)->get();
    }
}
