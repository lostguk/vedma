<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

final readonly class LogoutService
{
    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
