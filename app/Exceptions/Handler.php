<?php

namespace App\Exceptions;

use App\Exceptions\Api\ApiExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            if (request()->expectsJson()) {
                if ($e instanceof ModelNotFoundException) {
                    return new JsonResponse([
                        'status' => 'error',
                        'message' => 'Запрашиваемый ресурс не найден',
                    ], 404);
                }

                if ($e instanceof NotFoundHttpException) {
                    return new JsonResponse([
                        'status' => 'error',
                        'message' => 'Указанный маршрут не найден',
                    ], 404);
                }

                if ($e instanceof ValidationException) {
                    return new JsonResponse([
                        'status' => 'error',
                        'message' => 'Ошибка валидации данных',
                        'errors' => $e->errors(),
                    ], 422);
                }

                // Общий случай для остальных ошибок
                $statusCode = method_exists($e, 'getStatusCode') ?
                    $e->getStatusCode() : 500;

                $message = $statusCode === 500 ?
                    'Внутренняя ошибка сервера' : $e->getMessage();

                return new JsonResponse([
                    'status' => 'error',
                    'message' => $message,
                ], $statusCode);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            return (new ApiExceptionHandler)->handle($request, $e);
        }

        return parent::render($request, $e);
    }
}
