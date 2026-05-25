<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;

final readonly class ResendVerificationService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function send(User $user): void
    {
        $user->sendEmailVerificationNotification();
    }
}
