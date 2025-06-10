<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Services\Auth\ResetPasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @group Аутентификация
 *
 * API для восстановления пароля
 *
 * Позволяет сбросить пароль, используя ссылку из email.
 */
final class ResetPasswordController extends ApiController
{
    public function __construct(
        private readonly ResetPasswordService $resetPasswordService,
    ) {}

    /**
     * Сброс пароля
     *
     * Позволяет установить новый пароль, используя email и временный токен.
     *
     * @bodyParam email string required Email пользователя. Example: user@example.com
     * @bodyParam token string required Токен сброса, полученный из email. Example: abc123
     * @bodyParam password string required Новый пароль (минимум 8 символов). Example: NewPassword456
     * @bodyParam password_confirmation string required Подтверждение нового пароля. Example: NewPassword456
     *
     * @response 200 scenario="Пароль успешно изменён" {
     *   "message": "Пароль успешно изменён."
     * }
     * @response 422 scenario="Неверный или просроченный токен" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "token": ["Неверный или просроченный токен"]
     *   }
     * }
     * @throws ValidationException
     */
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $this->resetPasswordService->resetPassword($request->validated());

        return $this->successResponse([
            'message' => 'Пароль успешно изменён.',
        ]);
    }
}
