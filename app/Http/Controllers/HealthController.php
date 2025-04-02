<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            // Проверяем соединение с базой данных
            DB::connection()->getPdo();

            return response()->json([
                'status' => 'ok',
                'message' => 'Service is healthy',
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service is not healthy',
                'error' => $e->getMessage(),
                'timestamp' => now()->toIso8601String(),
            ], 503);
        }
    }
}
