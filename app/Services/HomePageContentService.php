<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\HomePageContent;
use App\Models\Product;
use App\Repositories\HomePageContentRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final readonly class HomePageContentService
{
    public function __construct(
        private HomePageContentRepository $repository,
    ) {
    }

    public function getHomePageContent(): HomePageContent
    {
        return $this->repository->getSingle();
    }

    /**
     * Получить товары для конкретной категории рекурсивно (включая дочерние).
     * Ограничение: максимум 3 товара.
     *
     * @param  Category  $category
     * @return Collection<int, Product>
     */
    public function getProductsForCategory(Category $category): Collection
    {
        $maxProductsPerCategory = 3;

        $categoryIds = collect([$category->id]);
        // Получаем все дочерние категории рекурсивно
        $descendants = $category->getAllDescendants();
        $categoryIds = $categoryIds->merge($descendants->pluck('id'))->unique()->values();

        if ($categoryIds->isEmpty()) {
            return collect();
        }

        // Получаем максимум 3 товара для этой категории и её потомков
        // Сортировка: от нового к старому по id (больший id = более новый товар)
        return Product::query()
            ->whereHas('categories', function (Builder $query) use ($categoryIds) {
                $query->whereIn('id', $categoryIds->all());
            })
            ->with(['categories', 'media'])
            ->orderBy('id', 'desc')
            ->limit($maxProductsPerCategory)
            ->get();
    }
}
