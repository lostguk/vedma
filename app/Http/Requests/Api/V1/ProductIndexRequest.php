<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|exists:categories,slug',
            'price_from' => 'nullable|numeric|gte:0',
            'price_to' => 'nullable|numeric|gte:0',
            'is_new' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'ids' => 'nullable|string',
            'sort' => [
                'nullable',
                'string',
                'in:price_asc,price_desc,name_asc,name_desc,created_at_desc',
            ],
            'per_page' => 'nullable|integer|gte:1|lte:100',
        ];
    }

    /**
     * Get parameters for query documentation for Scribe
     */
    public function queryParameters(): array
    {
        return [
            'search' => [
                'description' => 'Строка для поиска продуктов по названию',
                'example' => 'свеча ароматическая',
            ],
            'category' => [
                'description' => 'Slug категории для фильтрации продуктов',
                'example' => 'aromaticheskie-svechi',
            ],
            'price_from' => [
                'description' => 'Минимальная цена для фильтрации',
                'example' => 100.00,
                'type' => 'number',
            ],
            'price_to' => [
                'description' => 'Максимальная цена для фильтрации',
                'example' => 500.00,
                'type' => 'number',
            ],
            'is_new' => [
                'description' => 'Фильтр для отображения только новых продуктов',
                'example' => true,
                'type' => 'boolean',
            ],
            'is_bestseller' => [
                'description' => 'Фильтр для отображения только хитов продаж',
                'example' => true,
                'type' => 'boolean',
            ],
            'ids' => [
                'description' => 'Список ID продуктов через запятую для фильтрации',
                'example' => '1,2,3',
            ],
            'sort' => [
                'description' => 'Сортировка результатов',
                'example' => 'price_asc',
            ],
            'per_page' => [
                'description' => 'Количество результатов на странице (от 1 до 100)',
                'example' => 15,
                'type' => 'integer',
            ],
        ];
    }

    /**
     * Get the validated data with default values.
     *
     * @return array<string, mixed>
     */
    public function validatedWithDefaults(): array
    {
        $validated = $this->validated();

        // Set default values if not provided
        $validated['sort'] = $validated['sort'] ?? 'created_at_desc';
        $validated['per_page'] = (int) ($validated['per_page'] ?? 15);

        return $validated;
    }
}
