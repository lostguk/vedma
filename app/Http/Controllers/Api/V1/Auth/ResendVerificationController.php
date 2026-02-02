<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\ResendVerificationRequest;
use App\Models\User;
use App\Services\Auth\ResendVerificationService;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * @group Аутентификация
 *
 * Повторная отправка письма подтверждения email
 */
final class ResendVerificationController extends ApiController
{
    private const ERROR_EMAIL_NOT_FOUND = 'Пользователь не найден';

    private const ERROR_EMAIL_ALREADY_VERIFIED = 'Email адрес уже подтвержден';

    private const ERROR_VERIFICATION_EMAIL_FAILED = 'Не удалось отправить письмо для подтверждения. Проверьте адрес и попробуйте ещё раз.';

    private const SUCCESS_EMAIL_SENT = 'Письмо для подтверждения отправлено повторно.';

    public function __construct(
        private readonly ResendVerificationService $resendVerificationService,
    ) {}

    /**
     * Повторная отправка письма подтверждения
     *
     * @bodyParam email string required Email пользователя. Example: user@example.com
     *
     * @response 200 scenario="Письмо отправлено" {
     *     "status": "success",
     *     "message": "Письмо для подтверждения отправлено повторно.",
     *     "data": []
     * }
     * @response 200 scenario="Email уже подтвержден" {
     *     "status": "success",
     *     "message": "Email адрес уже подтвержден",
     *     "data": []
     * }
     * @response 404 scenario="Пользователь не найден" {
     *     "status": "error",
     *     "message": "Пользователь не найден"
     * }
     * @response 422 scenario="Не удалось отправить письмо" {
     *     "status": "error",
     *     "message": "Не удалось отправить письмо для подтверждения. Проверьте адрес и попробуйте ещё раз.",
     *     "errors": {
     *         "email": [
     *             "Не удалось доставить письмо подтверждения."
     *         ]
     *     }
     * }
     */
    public function __invoke(ResendVerificationRequest $request): JsonResponse
    {
        $user = $this->resendVerificationService->findByEmail(
            $request->string('email')->toString()
        );

        if (! $user instanceof User) {
            return $this->errorResponse(self::ERROR_EMAIL_NOT_FOUND, 404);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->successResponse([], self::ERROR_EMAIL_ALREADY_VERIFIED);
        }

        try {
            $this->resendVerificationService->send($user);
        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponse(
                self::ERROR_VERIFICATION_EMAIL_FAILED,
                422,
                ['email' => ['Не удалось доставить письмо подтверждения.']]
            );
        }

        return $this->successResponse([], self::SUCCESS_EMAIL_SENT);
    }
}
