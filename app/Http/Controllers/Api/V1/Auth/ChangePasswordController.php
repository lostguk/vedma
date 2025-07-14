<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\ChangePasswordRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\Auth\ChangePasswordService;
use Illuminate\Http\JsonResponse;

/**
 * @group Аутентификация
 *
 * API для управления учётной записью пользователя
 *
 * Позволяет аутентифицированному пользователю изменить свой пароль.
 */
final class ChangePasswordController extends ApiController
{
    public function __construct(
        private readonly ChangePasswordService $changePasswordService,
    ) {}

    /**
     * Смена пароля
     *
     * Этот эндпоинт позволяет сменить пароль авторизованного пользователя.
     * Текущий пароль проверяется автоматически на валидность.
     *
     * @authenticated
     *
     * @bodyParam current_password string required Текущий пароль. Example: oldpassword123
     * @bodyParam new_password string required Новый пароль (не менее 8 символов). Example: newpassword456
     * @bodyParam new_password_confirmation string required Подтверждение нового пароля. Example: newpassword456
     *
     * @response 200 scenario="Успешная смена пароля" {
     *   "message": "Пароль успешно обновлён.",
     *   "user": {
     *     "id": 1,
     *     "first_name": "Иван",
     *     "last_name": "Иванов",
     *     "email": "user@example.com",
     *     ...
     *   }
     * }
     * @response 422 scenario="Ошибка валидации" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "current_password": ["Текущий пароль указан неверно."]
     *   }
     * }
     */
    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $user = $this->changePasswordService->changePassword(
            user: $request->user(),
            newPassword: $request->input('new_password'),
        );

        return $this->successResponse([
            'message' => 'Пароль успешно обновлён.',
            'user' => new UserResource($user),
        ]);
    }
}
