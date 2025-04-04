<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Категории
 *
 * API для работы с категориями товаров
 *
 * Категории представляют иерархическую структуру типов товаров магазина.
 * Каждая категория может иметь родительскую категорию и дочерние категории,
 * что позволяет строить многоуровневое дерево категорий.
 *
 * ## Структура категории
 *
 * Каждая категория содержит следующие основные поля:
 * - `id` - Уникальный идентификатор категории
 * - `name` - Название категории
 * - `slug` - Уникальный текстовый идентификатор для URL
 * - `description` - Описание категории
 * - `icon` - URL иконки категории
 * - `parent_id` - ID родительской категории (null для корневых категорий)
 * - `sort_order` - Порядок сортировки
 * - `is_visible` - Флаг видимости категории
 * - `children` - Массив дочерних категорий (если запрошены)
 *
 * ## Использование API категорий
 *
 * API категорий позволяет получить как полный список категорий с их иерархией,
 * так и детальную информацию по отдельной категории. Для идентификации конкретной
 * категории используется её slug (например, "ritualnye-svechi").
 */
final class CategoryController extends ApiController
{
    /**
     * Получение списка категорий
     *
     * Возвращает список всех категорий магазина. По умолчанию включает все видимые категории.
     * Вы можете использовать параметр `show_hidden` для отображения скрытых категорий.
     *
     * @queryParam show_hidden boolean Показать скрытые категории. Example: false
     *
     * @response 200 scenario="Успешный запрос" {
     *     "data": [
     *         {
     *             "id": 1,
     *             "name": "Все свечи",
     *             "slug": "vse-svechi",
     *             "description": "Категория, включающая все типы свечей",
     *             "icon": "http://localhost:8000/storage/1/candle2.svg",
     *             "parent_id": null,
     *             "sort_order": 1,
     *             "is_visible": true,
     *             "children": [
     *                 {
     *                     "id": 2,
     *                     "name": "Ритуальные Свечи",
     *                     "slug": "ritualnye-svechi",
     *                     "description": "Свечи для различных ритуалов и церемоний",
     *                     "icon": "http://localhost:8000/storage/2/candle3.svg",
     *                     "parent_id": 1,
     *                     "sort_order": 1,
     *                     "is_visible": true
     *                 }
     *             ]
     *         }
     *     ]
     * }
     */
    public function index(): AnonymousResourceCollection
    {
        $query = Category::query()
            ->with(['media', 'children.media'])
            ->orderBy('sort_order');

        if (! request()->boolean('show_hidden')) {
            $query->visible();
        }

        $categories = $query->get();

        return CategoryResource::collection($categories);
    }

    /**
     * Получение категории по уникальному идентификатору (slug)
     *
     * Возвращает детальную информацию о категории включая дочерние категории.
     *
     * @urlParam slug string required Уникальный идентификатор категории. Example: ritualnye-svechi
     *
     * @response 200 scenario="Успешный запрос" {
     *     "data": {
     *         "id": 2,
     *         "name": "Ритуальные Свечи",
     *         "slug": "ritualnye-svechi",
     *         "description": "Свечи для различных ритуалов и церемоний",
     *         "icon": "http://localhost:8000/storage/2/candle3.svg",
     *         "parent_id": 1,
     *         "sort_order": 1,
     *         "is_visible": true,
     *         "children": [
     *             {
     *                 "id": 5,
     *                 "name": "Свечи для привлечения денег",
     *                 "slug": "svechi-dlya-privlecheniya-deneg",
     *                 "description": "Специальные свечи для денежных ритуалов",
     *                 "icon": "http://localhost:8000/storage/5/candle2.svg",
     *                 "parent_id": 2,
     *                 "sort_order": 1,
     *                 "is_visible": true
     *             },
     *             {
     *                 "id": 6,
     *                 "name": "Любовные свечи",
     *                 "slug": "lyubovnye-svechi",
     *                 "description": "Свечи для привлечения любви и укрепления отношений",
     *                 "icon": "http://localhost:8000/storage/6/candle2.svg",
     *                 "parent_id": 2,
     *                 "sort_order": 2,
     *                 "is_visible": true
     *             }
     *         ]
     *     }
     * }
     * @response 404 scenario="Категория не найдена" {
     *     "message": "No query results for model [App\\Models\\Category]."
     * }
     */
    public function show(string $slug): CategoryResource
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->with(['media', 'children.media', 'parent'])
            ->firstOrFail();

        return new CategoryResource($category);
    }
}
