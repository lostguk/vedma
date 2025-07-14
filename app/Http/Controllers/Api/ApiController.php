<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    /**
     * Success Response
     */
    protected function successResponse(mixed $data, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Error Response
     */
    protected function errorResponse(string $message, int $code = 400, ?array $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Response with no content
     */
    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Paginated Success Response
     *
     * Returns a success response with pagination metadata preserved in meta-property
     */
    protected function successPaginatedResponse(mixed $data, string $message = 'Success', int $code = 200): JsonResponse
    {
        $resource = $data->resource;
        $paginationData = $resource->toArray();

        // Extract the actual data items
        $items = $paginationData['data'] ?? [];

        // Remove data from pagination metadata
        unset($paginationData['data']);

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => [
                'data' => $items,
                'meta' => $paginationData,
            ],
        ], $code);
    }
}
