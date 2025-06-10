<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
use Illuminate\Http\JsonResponse;

/**
 * @group Аутентификация
 *
 * API для восстановления пароля
 *
 * Позволяет запросить ссылку для сброса пароля, которая придёт на email.
 */
final class ForgotPasswordController extends ApiController
{
    public function __construct(
        private readonly ForgotPasswordService $forgotPasswordService,
    ) {}

    /**
     * Запрос на сброс пароля
     *
     * Отправляет пользователю на email ссылку для сброса пароля, если пользователь существует.
     *
     * @bodyParam email string required Email пользователя. Example: user@example.com
     *
     * @response 200 scenario="Ссылка отправлена" {
     *   "message": "Ссылка на смену пароля отправлена."
     * }
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $this->forgotPasswordService->sendResetLink($request->validated('email'));

        return $this->successResponse([
            'message' => 'Ссылка на смену пароля отправлена.',
        ]);
    }
}
