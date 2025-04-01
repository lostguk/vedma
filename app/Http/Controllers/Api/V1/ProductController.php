<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(
    name: 'Products',
    description: 'API для работы с продуктами'
)]
final class ProductController extends ApiController
{
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
