<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use App\Services\Mail\MailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Сервис для отправки ссылки на восстановление пароля
 */
final readonly class ForgotPasswordService
{
    public function __construct(
        private UserRepository $userRepository,
        private MailService $mailService,
    ) {}

    /**
     * Отправить ссылку для восстановления пароля
     */
    public function sendResetLink(string $email): void
    {
        $this->userRepository->findByEmailOrFail($email);
        $frontendUrl = env('FRONTEND_URL');
        $plainToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($plainToken),
                'created_at' => now(),
            ]
        );

        $url = "{$frontendUrl}/reset-password?token={$plainToken}&email=" . urlencode($email);
        $this->mailService->sendPasswordResetLink($email, $url);
    }
}
