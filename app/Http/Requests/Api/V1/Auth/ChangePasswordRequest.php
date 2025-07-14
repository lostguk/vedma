<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends ApiRequest
{
    /**
     * Авторизован ли пользователь на выполнение запроса.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', Password::min(8), 'confirmed'],
            'new_password_confirmation' => ['required', 'string'],
        ];
    }

    /**
     * Документация тела запроса для Scribe
     *
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'current_password' => [
                'description' => 'Текущий пароль пользователя',
                'example' => 'oldpassword123',
            ],
            'new_password' => [
                'description' => 'Новый пароль (минимум 8 символов)',
                'example' => 'newpassword456',
            ],
            'new_password_confirmation' => [
                'description' => 'Подтверждение нового пароля (должно совпадать)',
                'example' => 'newpassword456',
            ],
        ];
    }

    /**
     * Пользовательские сообщения об ошибках.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Введите текущий пароль',
            'current_password.current_password' => 'Текущий пароль указан неверно',

            'new_password.required' => 'Введите новый пароль',
            'new_password.min' => 'Новый пароль должен содержать минимум 8 символов',
            'new_password.confirmed' => 'Новый пароль и его подтверждение не совпадают',

            'new_password_confirmation.required' => 'Подтвердите новый пароль',
        ];
    }
}
