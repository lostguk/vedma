<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    private UserRepository $userRepository;

    /**
     * RegistrationService constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     */
    public function register(array $data): User
    {
        // Хешируем пароль
        $data['password'] = Hash::make($data['password']);

        // Создаем пользователя
        /** @var User $user */
        $user = $this->userRepository->createWithVerification($data);

        // TODO: Отправка email для верификации будет добавлена позже

        return $user;
    }
}
