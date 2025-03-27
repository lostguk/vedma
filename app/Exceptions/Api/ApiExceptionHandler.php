<?php

namespace App\Exceptions\Api;

use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    use ApiResponseTrait;

    /**
     * Handle API exceptions
     */
    public function handle(Request $request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof ValidationException) {
            return $this->handleValidationException($exception);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException($exception);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->handleNotFoundHttpException($exception);
        }

        return $this->handleGenericException($exception);
    }

    /**
     * Handle generic exception
     */
    protected function handleGenericException(Throwable $exception): JsonResponse
    {
        $debug = config('app.debug');
        $message = $debug ? $exception->getMessage() : 'An unexpected error occurred.';

        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($debug) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        return response()->json($response, 500);
    }
}
