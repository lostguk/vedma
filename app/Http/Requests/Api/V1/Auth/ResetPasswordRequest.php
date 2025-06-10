<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Email пользователя, чей пароль нужно сбросить',
                'example' => 'user@example.com',
            ],
            'token' => [
                'description' => 'Токен сброса пароля из ссылки, полученной на email',
                'example' => 'abc123',
            ],
            'password' => [
                'description' => 'Новый пароль (минимум 8 символов)',
                'example' => 'newpassword456',
            ],
            'password_confirmation' => [
                'description' => 'Подтверждение нового пароля (должно совпадать)',
                'example' => 'newpassword456',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Введите email',
            'email.email' => 'Неверный формат email',

            'token.required' => 'Токен обязателен',
            'token.string' => 'Неверный формат токена',

            'password.required' => 'Введите новый пароль',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
            'password.confirmed' => 'Пароль и подтверждение не совпадают',

            'password_confirmation.required' => 'Подтвердите новый пароль',
        ];
    }
}
