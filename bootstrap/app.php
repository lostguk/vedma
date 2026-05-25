<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust upstream proxies to correctly detect HTTPS scheme
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_AWS_ELB);
        $middleware->group('api', [
            HandleCors::class,
        ]);
        $middleware->alias([
            'cors' => HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Illuminate\Http\Request $request) {
            if (
                $request->is('api/*') &&
                $e->getPrevious() instanceof Illuminate\Database\Eloquent\ModelNotFoundException
            ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Resource not found.',
                ], Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
            }
        });
    })->create();
