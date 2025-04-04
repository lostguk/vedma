<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\ProductIndexRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Services\ProductFilterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Продукты
 *
 * API для работы с продуктами магазина
 *
 * Продукты - основные товары, доступные в магазине магических товаров.
 * API предоставляет возможности для получения списка продуктов с фильтрацией,
 * сортировкой и пагинацией, а также детальной информации о конкретном продукте.
 *
 * ## Структура продукта
 *
 * Каждый продукт содержит следующие основные поля:
 * - `id` - Уникальный идентификатор продукта
 * - `name` - Название продукта
 * - `slug` - Уникальный текстовый идентификатор для URL
 * - `description` - Описание продукта
 * - `price` - Текущая цена
 * - `dimensions` - Физические характеристики (ширина, высота, глубина, вес)
 * - `categories` - Категории, к которым относится продукт
 * - `images_urls` - Массив URL изображений продукта
 * - `is_new` - Флаг новинки
 * - `is_bestseller` - Флаг хита продаж
 *
 * ## Фильтрация и сортировка
 *
 * API продуктов предоставляет разнообразные возможности фильтрации:
 * - По категории
 * - По ценовому диапазону
 * - По наличию статуса новинки или хита продаж
 * - По текстовому поиску в названии
 *
 * Доступные варианты сортировки:
 * - По цене (возрастание/убывание)
 * - По названию (возрастание/убывание)
 * - По дате добавления (убывание)
 */
final class ProductController extends ApiController
{
    /**
     * Получить список продуктов с фильтрацией, сортировкой и пагинацией.
     *
     * Этот эндпоинт возвращает пагинированный список продуктов с возможностью фильтрации
     * по различным параметрам, включая категорию, ценовой диапазон и статус "новинка".
     *
     * @queryParam search string Строка для поиска продуктов по названию. Example: свеча
     * @queryParam category string Slug категории для фильтрации продуктов. Example: aromaticheskie-svechi
     * @queryParam price_from numeric Минимальная цена для фильтрации. Example: 100
     * @queryParam price_to numeric Максимальная цена для фильтрации. Example: 500
     * @queryParam is_new boolean Фильтр для отображения только новых продуктов. Example: true
     * @queryParam is_bestseller boolean Фильтр для отображения только хитов продаж. Example: true
     * @queryParam sort string Сортировка результатов (price_asc, price_desc, name_asc, name_desc, created_at_desc). Example: price_asc
     * @queryParam per_page integer Количество результатов на странице (от 1 до 100). Example: 15
     *
     * @response 200 scenario="Успешный запрос" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Ароматическая свеча Лаванда",
     *       "slug": "aromaticheskaya-svecha-lavanda",
     *       "description": "Успокаивающий аромат лаванды для безмятежного отдыха",
     *       "price": 1200.99,
     *       "dimensions": {
     *         "width": 10,
     *         "height": 12,
     *         "depth": 10,
     *         "weight": 350
     *       },
     *       "images_urls": ["http://localhost:8000/storage/1/images/candle1.jpg"],
     *       "created_at": "2023-01-01T12:00:00.000000Z",
     *       "updated_at": "2023-01-01T12:00:00.000000Z"
     *     }
     *   ],
     *   "links": {
     *     "first": "/api/v1/products?page=1",
     *     "last": "/api/v1/products?page=5",
     *     "prev": null,
     *     "next": "/api/v1/products?page=2"
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 5,
     *     "path": "/api/v1/products",
     *     "per_page": 15,
     *     "to": 15,
     *     "total": 75
     *   }
     * }
     */
    public function index(ProductIndexRequest $request, ProductFilterService $filterService): AnonymousResourceCollection
    {
        // Get validated data with defaults
        $filters = $request->validatedWithDefaults();

        // Apply filters and sorting using the service
        $query = $filterService->apply($filters);

        // Add eager loading and paginate
        $products = $query->with(['categories', 'related.media'])
            ->paginate($filters['per_page']);

        return ProductResource::collection($products);
    }

    /**
     * Получить детальную информацию о продукте
     *
     * Этот эндпоинт возвращает детальную информацию о конкретном продукте, включая его категории,
     * связанные товары и медиафайлы. Продукт идентифицируется по его уникальному slug.
     *
     * @urlParam slug string required Уникальный идентификатор продукта. Example: aromaticheskaya-svecha-lavanda
     *
     * @response 200 scenario="Успешный запрос" {
     *   "data": {
     *     "id": 1,
     *     "name": "Ароматическая свеча Лаванда",
     *     "slug": "aromaticheskaya-svecha-lavanda",
     *     "description": "Успокаивающий аромат лаванды для безмятежного отдыха",
     *     "price": 1200.99,
     *     "dimensions": {
     *       "width": 10,
     *       "height": 12,
     *       "depth": 10,
     *       "weight": 350
     *     },
     *     "categories": [
     *       {
     *         "id": 1,
     *         "name": "Ароматические свечи",
     *         "slug": "aromaticheskie-svechi",
     *         "description": "Свечи с различными ароматами",
     *         "icon": "http://localhost:8000/storage/7/candle4.svg",
     *         "parent_id": null,
     *         "sort_order": 4,
     *         "is_visible": true
     *       }
     *     ],
     *     "related": [],
     *     "images_urls": ["http://localhost:8000/storage/1/images/candle1.jpg"],
     *     "created_at": "2023-01-01T12:00:00.000000Z",
     *     "updated_at": "2023-01-01T12:00:00.000000Z"
     *   }
     * }
     * @response 404 scenario="Продукт не найден" {
     *   "message": "Запрашиваемый ресурс не найден"
     * }
     */
    public function show(string $slug): ProductResource|JsonResponse
    {
        $product = Product::where('slug', $slug)->first();

        if (! $product) {
            return response()->json([
                'message' => 'Запрашиваемый ресурс не найден',
            ], Response::HTTP_NOT_FOUND);
        }

        $loadedProduct = $product->load(['categories', 'related.media']);

        return new ProductResource($loadedProduct);
    }
}
