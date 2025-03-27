<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    /**
     * Get list of categories
     */
    #[OA\Get(
        path: '/api/v1/categories',
        summary: 'Get list of categories',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(
                name: 'include_children',
                description: 'Include children categories in response',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'boolean'),
            ),
            new OA\Parameter(
                name: 'show_hidden',
                description: 'Show hidden categories',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'boolean'),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Category'),
                        ),
                    ],
                ),
            ),
        ],
    )]
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
     * Get category by slug
     */
    #[OA\Get(
        path: '/api/v1/categories/{slug}',
        summary: 'Get category by slug',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(
                name: 'slug',
                description: 'Category slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(ref: '#/components/schemas/Category'),
            ),
            new OA\Response(
                response: 404,
                description: 'Category not found',
                content: new OA\JsonContent(ref: '#/components/schemas/CategoryNotFound'),
            ),
        ],
    )]
    public function show(string $slug): CategoryResource
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->with(['media', 'children.media', 'parent'])
            ->firstOrFail();

        return new CategoryResource($category);
    }
}
