<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class ApiLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $startTime;

        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'duration' => round($duration * 1000, 2).'ms',
            'status' => $response->status(),
            'request_body' => $request->except(['password', 'password_confirmation']),
        ];

        if ($response->status() >= 400) {
            Log::channel('errors')->error('API Error', $logData);
        }

        if ($duration > 1.0) { // Если запрос выполняется дольше 1 секунды
            Log::channel('performance')->warning('Slow API Request', $logData);
        }

        Log::channel('api')->info('API Request', $logData);

        return $response;
    }
}
