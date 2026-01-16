<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRefundRequest extends FormRequest
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
            'amount' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.numeric' => 'Сумма возврата должна быть числом.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'amount' => [
                'description' => 'Сумма возврата (если не указана, возвращается полная сумма)',
                'example' => 1990.50,
            ],
        ];
    }
}
