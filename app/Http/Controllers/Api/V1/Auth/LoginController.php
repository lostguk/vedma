<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;

/**
 * @group Аутентификация
 *
 * Логин пользователя
 *
 * Позволяет получить токен доступа по email и паролю.
 *
 * @bodyParam email string required Email пользователя. Example: test@example.com
 * @bodyParam password string required Пароль пользователя. Example: password
 *
 * @response 200 {"status":"success","message":"Login successful","data":{"user":{},"token":"..."}}
 * @response 422 {"status":"error","message":"The given data was invalid.","errors":{"email":["..."],"password":["..."]}}
 */
final class LoginController extends ApiController
{
    public function __invoke(LoginRequest $request, LoginService $service): JsonResponse
    {
        $user = $service->attemptLogin(
            $request->string('email')->toString(),
            $request->string('password')->toString()
        );

        $token = $user->createToken('api')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successful');
    }
}
