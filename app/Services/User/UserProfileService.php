<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Repositories\UserRepository;

final readonly class UserProfileService
{
    public function getProfile(User $user): User
    {
        // Здесь может быть дополнительная бизнес-логика
        return $user;
    }

    public function updateProfile(User $user, array $data): User
    {
        $repo = app(UserRepository::class);

        return $repo->updateById($user->id, $data);
    }
}
