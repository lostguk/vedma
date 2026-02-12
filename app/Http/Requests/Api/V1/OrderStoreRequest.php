<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:products,id'],
            'items.*.count' => ['required', 'integer', 'gte:1'],
            'promo_code' => ['nullable', 'string', 'max:32'],
            'register' => ['required', 'boolean'],
            'delivery_type' => ['required', 'in:PostOffice,Cdek'],
            'delivery_price' => ['nullable', 'integer', 'min:0'],
            'first_name' => ['required', 'string', 'max:64'],
            'last_name' => ['required', 'string', 'max:64'],
            'middle_name' => ['nullable', 'string', 'max:64'],
            'email' => ['required', 'email', 'max:128'],
            'address' => ['required', 'string', 'max:255'],
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
            'delivery_type' => 'Некорректный тип доставки',
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
            'delivery_type' => [
                'description' => 'Тип доставки',
                'example' => 'post',
                'type' => 'string',
            ],
            'delivery_price' => [
                'description' => 'Стоимость доставки',
                'example' => 350,
                'type' => 'integer',
            ],
            'password' => [
                'description' => 'Пароль (если регистрация)',
                'example' => 'StrongPass123',
            ],
        ];
    }
}
