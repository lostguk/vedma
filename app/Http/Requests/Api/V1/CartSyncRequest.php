<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class CartSyncRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'items' => ['present', 'array', 'max:100'],
            'items.*.id' => ['required', 'integer', 'min:1'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:9999'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'items.present' => 'Передайте список товаров корзины.',
            'items.array' => 'Список товаров должен быть массивом.',
            'items.max' => 'В корзине не больше 100 позиций.',
            'items.*.id.required' => 'Укажите товар.',
            'items.*.id.integer' => 'Некорректный товар.',
            'items.*.quantity.required' => 'Укажите количество.',
            'items.*.quantity.min' => 'Количество должно быть больше нуля.',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'items' => [
                'description' => 'Позиции корзины. Пустой массив очищает корзину при полной замене.',
                'example' => [
                    ['id' => 1, 'quantity' => 2],
                ],
            ],
            'items.*.id' => [
                'description' => 'ID товара',
                'example' => 1,
            ],
            'items.*.quantity' => [
                'description' => 'Количество',
                'example' => 2,
            ],
        ];
    }
}
