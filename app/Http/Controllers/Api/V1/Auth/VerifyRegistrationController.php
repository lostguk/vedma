<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class VerifyRegistrationController extends ApiController
{
    const ERROR_INVALID_VERIFICATION_LINK = 'Неверная ссылка';

    const ERROR_EMAIL_ALREADY_VERIFIED = 'Адрес уже подтвержден';

    const SUCCESS_TEXT = 'Почта успешно подтверждена';

    public function __invoke(int $user, string $hash): JsonResponse
    {
        $user = User::findOrFail($user);

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return $this->errorResponse(self::ERROR_INVALID_VERIFICATION_LINK, 403);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse(self::ERROR_EMAIL_ALREADY_VERIFIED, 200);
        }

        $user->markEmailAsVerified();

        return $this->successResponse(
            [], self::SUCCESS_TEXT,
        );
    }
}
