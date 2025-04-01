<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     * Ensure this is clean or only contains default reportable.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Optional: logging logic
        });
        // NO renderable callbacks here for ModelNotFound/NotFoundHttp
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): Response
    {
        // Check specifically for NotFoundHttpException caused by ModelNotFoundException in API context
        if ($e instanceof NotFoundHttpException && $e->getPrevious() instanceof ModelNotFoundException) {
            // Use expectsJson() for API check
            if ($request instanceof Request && $request->expectsJson()) {
                return response()->json([
                    'message' => 'Запрашиваемый ресурс не найден',
                ], Response::HTTP_NOT_FOUND);
            }
        }

        // Fallback to the default handler
        return parent::render($request, $e);
    }
}
