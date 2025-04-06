<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\ApiRequest;

class RegisterRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+7\s?\(?\d{3}\)?\s?\d{3}[-\s]?\d{2}[-\s]?\d{2}$/'],
            'country' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
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
                'description' => 'Email пользователя (должен быть уникальным)',
                'example' => 'user@example.com',
            ],
            'password' => [
                'description' => 'Пароль (минимум 8 символов)',
                'example' => 'password123',
            ],
            'password_confirmation' => [
                'description' => 'Подтверждение пароля (должно совпадать с паролем)',
                'example' => 'password123',
            ],
            'phone' => [
                'description' => 'Номер телефона в формате +7 (XXX) XXX-XX-XX',
                'example' => '+7 (999) 123-45-67',
            ],
            'country' => [
                'description' => 'Страна',
                'example' => 'Россия',
            ],
            'region' => [
                'description' => 'Регион/область',
                'example' => 'Московская область',
            ],
            'city' => [
                'description' => 'Город',
                'example' => 'Москва',
            ],
            'postal_code' => [
                'description' => 'Почтовый индекс',
                'example' => '123456',
            ],
            'address' => [
                'description' => 'Адрес',
                'example' => 'ул. Пушкина, д. 1',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Поле Имя обязательно для заполнения',
            'last_name.required' => 'Поле Фамилия обязательно для заполнения',
            'middle_name.required' => 'Поле Отчество обязательно для заполнения',
            'email.required' => 'Поле E-mail обязательно для заполнения',
            'email.email' => 'Введите корректный E-mail адрес',
            'email.unique' => 'Пользователь с таким E-mail уже существует',
            'password.required' => 'Поле Пароль обязательно для заполнения',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'password_confirmation.required' => 'Поле Подтверждение пароля обязательно для заполнения',
            'phone.regex' => 'Неверный формат номера телефона. Используйте формат: +7 (XXX) XXX-XX-XX',
        ];
    }
}
