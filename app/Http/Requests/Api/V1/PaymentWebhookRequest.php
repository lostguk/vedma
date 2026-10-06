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
            'TerminalKey' => ['required', 'string'],
            'OrderId' => ['required', 'string'],
            'Success' => ['present'],
            'Status' => ['required', 'string'],
            'PaymentId' => ['required'],
            'ErrorCode' => ['required'],
            'Amount' => ['required', 'integer'],
            'Token' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'PaymentId' => [
                'description' => 'Идентификатор платежа в Т-Банке',
                'example' => '3093639567',
            ],
            'OrderId' => [
                'description' => 'public_id платежа в магазине',
                'example' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            ],
            'Status' => [
                'description' => 'Статус платежа Т-Банка',
                'example' => 'CONFIRMED',
            ],
            'Token' => [
                'description' => 'Подпись уведомления',
                'example' => 'sha256',
            ],
        ];
    }
}
