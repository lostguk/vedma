<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final class ProductFilterService
{
    /**
     * Apply filters to a Product query builder.
     *
     * @param  array<string, mixed>  $filters
     */
    public function apply(array $filters): Builder
    {
        $query = Product::query();

        // Search by name
        if (isset($filters['search']) && $filters['search']) {
            $query->search($filters['search']);
        }

        // Filter by category
        if (isset($filters['category']) && $filters['category']) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $filters['category']));
        }

        // Filter by price range
        if (isset($filters['price_from']) && is_numeric($filters['price_from'])) {
            $query->where('price', '>=', $filters['price_from']);
        }

        if (isset($filters['price_to']) && is_numeric($filters['price_to'])) {
            $query->where('price', '<=', $filters['price_to']);
        }

        // Filter by new products flag
        if (isset($filters['is_new']) && filter_var($filters['is_new'], FILTER_VALIDATE_BOOLEAN)) {
            $query->new();
        }

        // Filter by bestseller flag
        if (isset($filters['is_bestseller']) && filter_var($filters['is_bestseller'], FILTER_VALIDATE_BOOLEAN)) {
            $query->bestseller();
        }

        // Filter by specific product IDs
        if (isset($filters['ids']) && $filters['ids']) {
            $idsArray = explode(',', $filters['ids']);
            $idsArray = array_filter(array_map('intval', $idsArray), fn ($id) => $id > 0);

            if (! empty($idsArray)) {
                $query->whereIn('id', $idsArray);
            }
        }

        // Apply sorting
        $sortParam = $filters['sort'] ?? 'created_at_desc';
        match ($sortParam) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return $query;
    }
}
