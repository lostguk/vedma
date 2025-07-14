<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * Сервис для смены пароля пользователя
 */
final readonly class ChangePasswordService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    /**
     * Сменить пароль пользователя
     */
    public function changePassword(User $user, string $newPassword): User
    {
        $hashedPassword = Hash::make($newPassword);

        return $this->userRepository->updatePassword($user, $hashedPassword);
    }
}
