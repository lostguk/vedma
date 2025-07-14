<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * Сервис для регистрации новых пользователей
 */
final readonly class RegistrationService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    /**
     * Регистрация нового пользователя
     *
     * @param array{
     *     email: string,
     *     password: string,
     *     first_name: string,
     *     last_name: string,
     *     middle_name?: string|null,
     *     phone?: string|null
     * } $data Данные пользователя
     */
    public function register(array $data): User
    {
        // Хешируем пароль
        $data['password'] = Hash::make($data['password']);

        // Создаем пользователя
        // TODO: Отправка email для верификации будет добавлена позже

        return $this->userRepository->createWithVerification($data);
    }
}
