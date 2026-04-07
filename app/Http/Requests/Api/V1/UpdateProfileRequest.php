<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id() ?? 0;

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

    public function messages(): array
    {
        return [
            'first_name.required' => 'Поле Имя обязательно для заполнения',
            'first_name.max' => 'Имя не должно превышать 255 символов',
            'last_name.required' => 'Поле Фамилия обязательно для заполнения',
            'last_name.max' => 'Фамилия не должна превышать 255 символов',
            'middle_name.max' => 'Отчество не должно превышать 255 символов',
            'email.required' => 'Поле Email обязательно для заполнения',
            'email.email' => 'Введите корректный Email адрес',
            'email.unique' => 'Пользователь с таким Email уже существует',
            'phone.max' => 'Номер телефона слишком длинный',
            'address.max' => 'Адрес не должен превышать 255 символов',
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
