<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class ShippingCalculateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'integer', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'gte:1'],
            'address' => ['required', 'string', 'min:5', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'Необходимо передать массив товаров.',
            'products.array' => 'Товары должны быть массивом.',
            'products.*.id.required' => 'ID товара обязателен.',
            'products.*.id.integer' => 'ID товара должен быть числом.',
            'products.*.id.exists' => 'Товар с таким ID не найден.',
            'products.*.quantity.required' => 'Количество товара обязательно.',
            'products.*.quantity.integer' => 'Количество товара должно быть числом.',
            'products.*.quantity.min' => 'Количество товара должно быть не менее 1.',
            'address.required' => 'Адрес обязателен.',
            'address.string' => 'Адрес должен быть строкой.',
            'address.min' => 'Адрес слишком короткий.',
            'address.max' => 'Адрес слишком длинный.',
        ];
    }

    /**
     * Scribe: описание параметров тела запроса
     *
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'products' => [
                'description' => 'Список товаров для расчёта. Каждый элемент содержит id товара и количество.',
                'type' => 'array',
                'example' => [
                    ['id' => 1, 'quantity' => 2],
                    ['id' => 5, 'quantity' => 1],
                ],
            ],
            'products.*.id' => [
                'description' => 'ID товара из каталога.',
                'type' => 'integer',
                'example' => 1,
            ],
            'products.*.quantity' => [
                'description' => 'Количество единиц товара (не менее 1).',
                'type' => 'integer',
                'example' => 2,
            ],
            'address' => [
                'description' => 'Адрес доставки в формате с индексом (пример из доков Metaship).',
                'type' => 'string',
                'example' => '191025, г Санкт-Петербург, Центральный р-н, Невский пр-кт, д 106',
            ],
        ];
    }
}
