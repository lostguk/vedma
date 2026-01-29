<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @group Аутентификация
 *
 * API для аутентификации пользователей
 *
 * Для входа в систему используйте эндпоинт `POST /api/v1/login`.
 * После успешной аутентификации вы получите токен доступа, который нужно использовать в заголовке Authorization.
 *
 * ## Формат данных для входа
 *
 * Данные для входа должны быть отправлены в формате JSON с необходимыми полями:
 * - `email` - Электронный адрес пользователя
 * - `password` - Пароль пользователя
 */
final class LoginController extends ApiController
{
    private const ERROR_EMAIL_NOT_VERIFIED = 'Email адрес не подтвержден';

    /**
     * Вход в систему
     *
     * Позволяет получить токен доступа по email и паролю.
     * После успешной аутентификации возвращается токен доступа и данные пользователя.
     *
     * @bodyParam email string required Email пользователя. Example: gusengus57@gmail.com
     * @bodyParam password string required Пароль пользователя. Example: password123
     *
     * @response 200 scenario="Успешный вход" {
     *     "status": "success",
     *     "message": "Login successful",
     *     "data": {
     *         "user": {
     *             "id": 1,
     *             "first_name": "Иван",
     *             "last_name": "Иванов",
     *             "middle_name": "Иванович",
     *             "full_name": "Иванов Иван Иванович",
     *             "email": "gusengus57@gmail.com",
     *             "phone": "+79001234567",
     *             "address": "Россия"
     *             "email_verified": true,
     *             "created_at": "2023-01-01T00:00:00+00:00",
     *             "updated_at": "2023-01-01T00:00:00+00:00"
     *         },
     *         "token": "1|laravel_sanctum_hashed_token_example_123456789"
     *     }
     * }
     * @response 422 scenario="Ошибка валидации" {
     *     "status": "error",
     *     "message": "The given data was invalid.",
     *     "errors": {
     *         "email": [
     *             "Указанные учетные данные не соответствуют нашим записям."
     *         ],
     *         "password": [
     *             "Пароль должен содержать не менее 8 символов."
     *         ]
     *     }
     * }
     * @response 403 scenario="Email не подтвержден" {
     *     "status": "error",
     *     "message": "Email адрес не подтвержден"
     * }
     */
    public function __invoke(LoginRequest $request, LoginService $service): JsonResponse
    {
        $user = $service->attemptLogin(
            $request->string('email')->toString(),
            $request->string('password')->toString()
        );

        if (! $user->hasVerifiedEmail()) {
            Auth::logout();

            return $this->errorResponse(self::ERROR_EMAIL_NOT_VERIFIED, 403);
        }

        $token = $user->createToken('api')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successful');
    }
}
