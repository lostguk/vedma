<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam items object[] required Массив позиций заказа. Example: [{"id":1,"count":2}]
 * @bodyParam items[].id int required ID товара. Example: 1
 * @bodyParam items[].count int required Количество. Example: 2
 * @bodyParam promo_code string Промокод. Example: PROMO2208
 * @bodyParam register boolean required Зарегистрировать пользователя. Example: true
 * @bodyParam first_name string required Имя пользователя. Example: Admin
 * @bodyParam last_name string required Фамилия пользователя. Example: System
 * @bodyParam middle_name string required Отчество пользователя. Example: Root
 * @bodyParam email string required Email пользователя. Example: admin@admin.ru
 * @bodyParam phone string Телефон пользователя. Example: +7 999 999 99 99
 * @bodyParam address string required Адрес доставки. Example: ул. Администраторская, д. 1
 * @bodyParam password string Пароль (если регистрация). Example: StrongPass123
 */
class OrderStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:products,id'],
            'items.*.count' => ['required', 'integer', 'gte:1'],
            'promo_code' => ['nullable', 'string', 'max:32'],
            'register' => ['required', 'boolean'],
            'first_name' => ['required', 'string', 'max:64'],
            'last_name' => ['required', 'string', 'max:64'],
            'middle_name' => ['nullable', 'string', 'max:64'],
            'email' => ['required', 'email', 'max:128'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required_if:register,true', 'string', 'min:8', 'max:64'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Необходимо передать товары для заказа.',
            'items.*.id.exists' => 'Товар не найден.',
            'register.required' => 'Необходимо указать, требуется ли регистрация.',
            'first_name.required' => 'Имя обязательно.',
            'last_name.required' => 'Фамилия обязательна.',
            'email.required' => 'Email обязателен.',
            'email.email' => 'Некорректный email.',
            'password.required_if' => 'Пароль обязателен при регистрации.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('email', 'unique:users,email', function ($input) {
            return (bool) ($input->register ?? false);
        });
    }

    /**
     * Get parameters for body documentation for Scribe
     */
    public function bodyParameters(): array
    {
        return [
            'items' => [
                'description' => 'Массив позиций заказа',
                'example' => [['id' => 1, 'count' => 2]],
            ],
            'items.*.id' => [
                'description' => 'ID товара',
                'example' => 1,
            ],
            'items.*.count' => [
                'description' => 'Количество',
                'example' => 2,
            ],
            'promo_code' => [
                'description' => 'Промокод',
                'example' => 'PROMO2208',
            ],
            'register' => [
                'description' => 'Зарегистрировать пользователя',
                'example' => true,
            ],
            'first_name' => [
                'description' => 'Имя пользователя',
                'example' => 'Admin',
            ],
            'last_name' => [
                'description' => 'Фамилия пользователя',
                'example' => 'System',
            ],
            'middle_name' => [
                'description' => 'Отчество пользователя',
                'example' => 'Root',
            ],
            'email' => [
                'description' => 'Email пользователя',
                'example' => 'admin@admin.ru',
            ],
            'phone' => [
                'description' => 'Телефон пользователя',
                'example' => '+7 999 999 99 99',
            ],
            'address' => [
                'description' => 'Адрес доставки',
                'example' => 'ул. Администраторская, д. 1',
            ],
            'password' => [
                'description' => 'Пароль (если регистрация)',
                'example' => 'StrongPass123',
            ],
        ];
    }
}
