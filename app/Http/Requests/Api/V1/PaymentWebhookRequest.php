<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentWebhookRequest extends FormRequest
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
            'orderId' => ['nullable', 'string', 'required_without:mdOrder'],
            'mdOrder' => ['nullable', 'string', 'required_without:orderId'],
            'orderStatus' => ['nullable', 'integer'],
            'status' => ['nullable', 'string'],
            'amount' => ['nullable', 'integer'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'orderId' => [
                'description' => 'ID заказа в платежной системе',
                'example' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            ],
            'orderStatus' => [
                'description' => 'Статус заказа по шкале платежного шлюза',
                'example' => 2,
            ],
        ];
    }
}
