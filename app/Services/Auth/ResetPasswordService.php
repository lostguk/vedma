<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Сервис для сброса пароля по токену
 */
final readonly class ResetPasswordService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    /**
     * Сбросить пароль пользователя по email и токену
     *
     * @param  array{email: string, token: string, password: string, password_confirmation: string}  $data
     *
     * @throws ValidationException
     */
    public function resetPassword(array $data): void
    {
        $user = $this->userRepository->findByEmailOrFail($data['email']);

        $record = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->first();

        if (! $record || ! Hash::check($data['token'], $record->token)) {
            throw ValidationException::withMessages([
                'token' => ['Неверный токен'],
            ]);
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            throw ValidationException::withMessages([
                'token' => ['Срок действия токена истёк'],
            ]);
        }

        $hashedPassword = Hash::make($data['password']);

        $this->userRepository->updatePassword($user, $hashedPassword);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();
    }
}
