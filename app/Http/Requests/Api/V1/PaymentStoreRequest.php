<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'success_url' => ['nullable', 'url', 'max:2048'],
            'fail_url' => ['nullable', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Необходимо передать идентификатор заказа.',
            'order_id.exists' => 'Заказ не найден.',
            'success_url.url' => 'Некорректный URL для успешной оплаты.',
            'fail_url.url' => 'Некорректный URL для ошибки оплаты.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'order_id' => [
                'description' => 'ID заказа',
                'example' => 1,
            ],
            'success_url' => [
                'description' => 'URL для редиректа после успешной оплаты',
                'example' => 'https://shop.example.com/payment/success',
            ],
            'fail_url' => [
                'description' => 'URL для редиректа после ошибки оплаты',
                'example' => 'https://shop.example.com/payment/fail',
            ],
        ];
    }
}
