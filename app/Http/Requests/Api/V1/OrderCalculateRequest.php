<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class OrderCalculateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:products,id'],
            'items.*.count' => ['required', 'integer', 'gte:1'],
            'promo_code' => ['nullable', 'string', 'max:32'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Необходимо передать товары для расчета.',
            'items.*.id.exists' => 'Товар не найден.',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'items' => [
                'description' => 'Список товаров для расчета',
                'example' => [
                    ['id' => 1, 'count' => 3],
                ],
            ],
            'items.*.id' => [
                'description' => 'ID товара',
                'example' => 1,
            ],
            'items.*.count' => [
                'description' => 'Количество товара',
                'example' => 3,
            ],
            'promo_code' => [
                'description' => 'Промокод (опционально)',
                'example' => 'PROMO10',
            ],
        ];
    }
}
