<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\Auth\RegistrationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

/**
 * @group Аутентификация
 *
 * API для регистрации и авторизации пользователей
 *
 * Для работы с API вам необходимо зарегистрировать пользователя через эндпоинт `POST /api/v1/register`.
 * После успешной регистрации вы получите доступ ко всем эндпоинтам API.
 *
 * ## Формат регистрационных данных
 *
 * Регистрационные данные должны быть отправлены в формате JSON с необходимыми полями:
 * - `first_name` - Имя пользователя
 * - `last_name` - Фамилия пользователя
 * - `email` - Электронный адрес (должен быть уникальным)
 * - `password` - Пароль (минимум 8 символов)
 *
 * Остальные поля являются опциональными:
 * - `middle_name` - Отчество
 * - `phone` - Номер телефона
 * - `country`, `region`, `city`, `postal_code`, `address` - Адресные данные
 */
final class RegisterController extends ApiController
{
    private RegistrationService $registrationService;

    /**
     * RegisterController constructor.
     */
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Регистрация нового пользователя
     *
     * Этот эндпоинт позволяет создать нового пользователя. После успешной регистрации возвращается токен доступа
     * и данные пользователя. Обязательными полями являются first_name, last_name, email и password.
     *
     * @bodyParam first_name string required Имя пользователя. Example: Иван
     * @bodyParam last_name string required Фамилия пользователя. Example: Иванов
     * @bodyParam middle_name string required Отчество пользователя. Example: Иванович
     * @bodyParam email string required Email пользователя (должен быть уникальным). Example: user@example.com
     * @bodyParam password string required Пароль (минимум 8 символов). Example: password123
     * @bodyParam password_confirmation string required Пароль (минимум 8 символов). Example: password123
     * @bodyParam phone string Номер телефона в формате +7 (XXX) XXX-XX-XX. Example: +7 (999) 123-45-67
     * @bodyParam country string Страна. Example: Россия
     * @bodyParam region string Регион/область. Example: Московская область
     * @bodyParam city string Город. Example: Москва
     * @bodyParam postal_code string Почтовый индекс. Example: 123456
     * @bodyParam address string Адрес. Example: ул. Пушкина, д. 1
     *
     * @response 201 scenario="Успешная регистрация" {
     *     "token": "9|MfOSV0Iqv4yGIJZGMhUZpzb4Yjs24rGhQHZJ7zOY",
     *     "user": {
     *         "id": 11,
     *         "first_name": "Иван",
     *         "last_name": "Иванов",
     *         "middle_name": "Иванович",
     *         "email": "user@example.com",
     *         "email_verified_at": null,
     *         "phone": "+7 (999) 123-45-67",
     *         "country": "Россия",
     *         "region": "Московская область",
     *         "city": "Москва",
     *         "postal_code": "123456",
     *         "address": "ул. Пушкина, д. 1",
     *         "created_at": "2023-04-04T12:30:45.000000Z",
     *         "updated_at": "2023-04-04T12:30:45.000000Z"
     *     }
     * }
     * @response 422 scenario="Ошибка валидации" {
     *     "message": "The given data was invalid.",
     *     "errors": {
     *         "email": [
     *             "The email has already been taken."
     *         ]
     *     }
     * }
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = $this->registrationService->register($request->validated());

        event(new Registered($user));

        return $this->successResponse(
            new UserResource($user),
            'Регистрация успешно завершена. Пожалуйста, проверьте вашу почту для подтверждения email.',
        );
    }
}
