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
     * Получить товары из категорий рекурсивно.
     * Ограничение: максимум 3 товара на каждую категорию.
     *
     * @param  Collection<int, Category>  $categories
     * @return Collection<int, Product>
     */
    public function getProductsFromCategories(Collection $categories): Collection
    {
        $allProducts = collect();
        $maxProductsPerCategory = 3;

        foreach ($categories as $category) {
            $categoryIds = collect([$category->id]);
            // Получаем все дочерние категории рекурсивно
            $descendants = $category->getAllDescendants();
            $categoryIds = $categoryIds->merge($descendants->pluck('id'))->unique()->values();

            if ($categoryIds->isEmpty()) {
                continue;
            }

            // Получаем максимум 3 товара для этой категории и её потомков
            $categoryProducts = Product::query()
                ->whereHas('categories', function (Builder $query) use ($categoryIds) {
                    $query->whereIn('id', $categoryIds->all());
                })
                ->with(['categories', 'media'])
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->limit($maxProductsPerCategory)
                ->get();

            $allProducts = $allProducts->merge($categoryProducts);
        }

        // Убираем дубликаты товаров (если товар есть в нескольких категориях)
        return $allProducts->unique('id')->values();
    }
}
