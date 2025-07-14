<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->id ?? 0;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'phone' => ['nullable', 'string', 'max:32'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get parameters for body documentation for Scribe
     */
    public function bodyParameters(): array
    {
        return [
            'first_name' => [
                'description' => 'Имя пользователя',
                'example' => 'Иван',
            ],
            'last_name' => [
                'description' => 'Фамилия пользователя',
                'example' => 'Иванов',
            ],
            'middle_name' => [
                'description' => 'Отчество пользователя',
                'example' => 'Иванович',
            ],
            'email' => [
                'description' => 'Email пользователя',
                'example' => 'user@example.com',
            ],
            'phone' => [
                'description' => 'Номер телефона пользователя',
                'example' => '+7 (999) 123-45-67',
            ],
            'address' => [
                'description' => 'Адрес',
                'example' => 'ул. Пушкина, д. 1',
            ],
        ];
    }
}
