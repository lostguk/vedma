<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\ApiRequest;

final class LoginRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    /**
     * Get parameters for body documentation for Scribe
     */
    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Email пользователя',
                'example' => 'user@example.com',
            ],
            'password' => [
                'description' => 'Пароль пользователя (минимум 8 символов)',
                'example' => 'password123',
            ],
        ];
    }
}
