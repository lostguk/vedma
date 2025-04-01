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
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(
    name: 'Products',
    description: 'API для работы с продуктами'
)]
final class ProductController extends ApiController
{
    /**
     * Получить список продуктов с фильтрацией, сортировкой и пагинацией.
     */
    #[OA\Get(
        path: '/api/v1/products',
        operationId: 'getProductsList',
        summary: 'Получить список продуктов',
        description: 'Возвращает пагинированный список продуктов с возможностью фильтрации и сортировки.',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'search', description: 'Строка для поиска по названию продукта', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'category', description: 'Slug категории для фильтрации', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'price_from', description: 'Минимальная цена', in: 'query', required: false, schema: new OA\Schema(type: 'number', format: 'float', minimum: 0)),
            new OA\Parameter(name: 'price_to', description: 'Максимальная цена', in: 'query', required: false, schema: new OA\Schema(type: 'number', format: 'float', minimum: 0)),
            new OA\Parameter(name: 'is_new', description: 'Фильтр по новинкам (1 или true)', in: 'query', required: false, schema: new OA\Schema(type: 'boolean')),
            new OA\Parameter(name: 'is_bestseller', description: 'Фильтр по хитам продаж (1 или true)', in: 'query', required: false, schema: new OA\Schema(type: 'boolean')),
            new OA\Parameter(name: 'ids', description: 'Список ID продуктов через запятую', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'sort', description: 'Поле и направление сортировки', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['price_asc', 'price_desc', 'name_asc', 'name_desc', 'created_at_desc'])),
            new OA\Parameter(name: 'page', description: 'Номер страницы пагинации', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', description: 'Количество элементов на странице (макс: 100)', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Успешный ответ со списком продуктов',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Product')),
                        new OA\Property(property: 'links', properties: [
                            new OA\Property(property: 'first', type: 'string', format: 'url', nullable: true, example: '/api/v1/products?page=1'),
                            new OA\Property(property: 'last', type: 'string', format: 'url', nullable: true, example: '/api/v1/products?page=10'),
                            new OA\Property(property: 'prev', type: 'string', format: 'url', nullable: true, example: null),
                            new OA\Property(property: 'next', type: 'string', format: 'url', nullable: true, example: '/api/v1/products?page=2'),
                        ], type: 'object'),
                        new OA\Property(property: 'meta', properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'from', type: 'integer', nullable: true, example: 1),
                            new OA\Property(property: 'last_page', type: 'integer', example: 10),
                            new OA\Property(property: 'links', type: 'array', items: new OA\Items(properties: [
                                new OA\Property(property: 'url', type: 'string', format: 'url', nullable: true, example: '/api/v1/products?page=1'),
                                new OA\Property(property: 'label', type: 'string', example: '1'),
                                new OA\Property(property: 'active', type: 'boolean', example: true),
                            ], type: 'object')),
                            new OA\Property(property: 'path', type: 'string', format: 'url', example: '/api/v1/products'),
                            new OA\Property(property: 'per_page', type: 'integer', example: 15),
                            new OA\Property(property: 'to', type: 'integer', nullable: true, example: 15),
                            new OA\Property(property: 'total', type: 'integer', example: 150),
                        ], type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Ошибка валидации параметров запроса',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                    new OA\Property(property: 'errors', type: 'object', example: ['category' => ['The selected category is invalid.']]),
                ])
            ),
        ]
    )]
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
     */
    #[OA\Get(
        path: '/api/v1/products/{slug}',
        operationId: 'getProductBySlug',
        summary: 'Получить детальную информацию о продукте',
        description: 'Возвращает детальную информацию о продукте, включая его категории и рекомендуемые товары',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'slug',
                description: 'Slug продукта (например: iphone-13)',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
                example: 'iphone-13'
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Успешный ответ',
                content: new OA\JsonContent(ref: '#/components/schemas/ProductResponse')
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Продукт не найден',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ],
    )]
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
