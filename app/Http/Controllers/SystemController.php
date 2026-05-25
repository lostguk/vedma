<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class SystemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Магазин магических товаров API',
            'version' => '1.0.0',
            'status' => 'working',
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function health(): Response
    {
        return response('healthy', 200)
            ->header('Content-Type', 'text/plain');
    }
}
