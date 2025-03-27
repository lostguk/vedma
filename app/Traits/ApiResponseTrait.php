<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ApiResponseTrait
{
    /**
     * Handle validation exception
     */
    protected function handleValidationException(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'The given data was invalid.',
            'errors' => $exception->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Handle model not found exception
     */
    protected function handleModelNotFoundException(ModelNotFoundException $exception): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Resource not found.',
            'errors' => [
                'model' => ['The requested resource could not be found.'],
            ],
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Handle not found http exception
     */
    protected function handleNotFoundHttpException(NotFoundHttpException $exception): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'The requested resource could not be found.',
            'errors' => [
                'url' => ['The requested URL was not found.'],
            ],
        ], Response::HTTP_NOT_FOUND);
    }
}
