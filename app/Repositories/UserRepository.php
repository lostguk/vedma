<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Create a new user with verification token
     */
    public function createWithVerification(array $payload): ?User
    {
        $payload['email_verification_token'] = Str::random(64);

        /** @var User $user */
        $user = $this->create($payload);

        return $user;
    }
}
