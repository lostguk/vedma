<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Services\Auth\LogoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Аутентификация
 *
 * Логаут пользователя
 *
 * Завершает сессию пользователя и отзывает текущий токен.
 *
 * @authenticated
 *
 * @response 204 {}
 * @response 401 {"message":"Unauthenticated."}
 */
final class LogoutController extends ApiController
{
    public function __invoke(Request $request, LogoutService $service): JsonResponse
    {
        $service->logout($request->user());

        return $this->successResponse(null, 'Logout successful');
    }
}
