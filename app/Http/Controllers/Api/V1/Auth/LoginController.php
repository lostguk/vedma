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
    /**
     * Вход в систему
     *
     * Позволяет получить токен доступа по email и паролю.
     * После успешной аутентификации возвращается токен доступа и данные пользователя.
     *
     * @bodyParam email string required Email пользователя. Example: user@example.com
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
     *             "email": "user@example.com",
     *             "phone": "+79001234567",
     *             "address": {
     *                 "country": "Россия",
     *                 "region": "Московская область",
     *                 "city": "Москва",
     *                 "postal_code": "123456",
     *                 "address": "ул. Примерная, д. 1, кв. 1"
     *             },
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
     */
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
