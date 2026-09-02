<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * @group Аутентификация
 *
 * Подтверждение email адреса пользователя
 */
final class VerifyRegistrationController extends ApiController
{
    const ERROR_INVALID_VERIFICATION_LINK = 'Неверная или истекшая ссылка подтверждения. Запросите новое письмо.';

    const ERROR_EMAIL_ALREADY_VERIFIED = 'Email адрес уже подтвержден';

    const SUCCESS_TEXT = 'Email успешно подтвержден';

    /**
     * Подтверждение email адреса
     *
     * Этот эндпоинт используется для подтверждения email адреса пользователя.
     * Ссылка генерируется автоматически при регистрации и отправляется на email.
     * Ссылка действительна в течение 24 часов и содержит цифровую подпись для безопасности.
     * После успешного подтверждения возвращается токен доступа.
     *
     * @urlParam user integer required ID пользователя. Example: 1
     * @urlParam hash string required Хеш email адреса (sha1). Example: 5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8
     *
     * @queryParam expires integer required Unix‑timestamp, срок действия ссылки. Example: 1738156800
     * @queryParam signature string required Подпись ссылки, формируется приложением. Example: 2b64a6c0a1f7a5d9cbb7f0e3c0a8b1a9d3c1f5e6
     *
     * @response 200 scenario="Успешное подтверждение" {
     *     "status": "success",
     *     "message": "Email успешно подтвержден",
     *     "data": {
     *         "user": {},
     *         "token": "1|laravel_sanctum_token"
     *     }
     * }
     * @response 200 scenario="Email уже подтвержден" {
     *     "status": "success",
     *     "message": "Email адрес уже подтвержден",
     *     "data": {
     *         "user": {},
     *         "token": "1|laravel_sanctum_token"
     *     }
     * }
     * @response 403 scenario="Неверная ссылка" {
     *     "status": "error",
     *     "message": "Неверная или истекшая ссылка подтверждения. Запросите новое письмо."
     * }
     * @response 404 scenario="Пользователь не найден" {
     *     "message": "User not found"
     * }
     */
    public function __invoke(int $user, string $hash): JsonResponse
    {
        $user = User::findOrFail($user);

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return $this->errorResponse(self::ERROR_INVALID_VERIFICATION_LINK, 403);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->authenticatedResponse($user, self::ERROR_EMAIL_ALREADY_VERIFIED);
        }

        $user->markEmailAsVerified();

        return $this->authenticatedResponse($user, self::SUCCESS_TEXT);
    }

    private function authenticatedResponse(User $user, string $message): JsonResponse
    {
        $token = $user->createToken('api')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user->fresh()),
            'token' => $token,
        ], $message);
    }
}
