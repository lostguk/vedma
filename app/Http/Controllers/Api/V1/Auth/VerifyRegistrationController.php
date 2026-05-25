<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * @group Аутентификация
 *
 * Подтверждение email адреса пользователя
 */
final class VerifyRegistrationController extends ApiController
{
    const ERROR_INVALID_VERIFICATION_LINK = 'Неверная или истекшая ссылка подтверждения';

    const ERROR_EMAIL_ALREADY_VERIFIED = 'Email адрес уже подтвержден';

    const SUCCESS_TEXT = 'Email успешно подтвержден';

    /**
     * Подтверждение email адреса
     *
     * Этот эндпоинт используется для подтверждения email адреса пользователя.
     * Ссылка генерируется автоматически при регистрации и отправляется на email.
     * Ссылка действительна в течение 60 минут и содержит цифровую подпись для безопасности.
     *
     * @urlParam user integer required ID пользователя. Example: 1
     * @urlParam hash string required Хеш email адреса (sha1). Example: 5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8
     *
     * @queryParam expires integer required Unix‑timestamp, срок действия ссылки. Example: 1738156800
     * @queryParam signature string required Подпись ссылки, формируется приложением. Example: 2b64a6c0a1f7a5d9cbb7f0e3c0a8b1a9d3c1f5e6
     *
     * @response 200 scenario="Успешное подтверждение" {
     *     "message": "Email успешно подтвержден"
     * }
     * @response 200 scenario="Email уже подтвержден" {
     *     "message": "Email адрес уже подтвержден"
     * }
     * @response 403 scenario="Неверная ссылка" {
     *     "message": "Неверная или истекшая ссылка подтверждения"
     * }
     * @response 404 scenario="Пользователь не найден" {
     *     "message": "User not found"
     * }
     */
    public function __invoke(int $user, string $hash): JsonResponse
    {
        $user = User::findOrFail($user);

        // Проверяем соответствие хеша
        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return $this->errorResponse(self::ERROR_INVALID_VERIFICATION_LINK, 403);
        }

        // Проверяем, не подтвержден ли уже email
        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse(self::ERROR_EMAIL_ALREADY_VERIFIED, 200);
        }

        // Подтверждаем email
        $user->markEmailAsVerified();

        return $this->successResponse(
            [],
            self::SUCCESS_TEXT,
        );
    }
}
