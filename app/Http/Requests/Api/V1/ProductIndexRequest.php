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
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0|gte:price_from',
            'is_new' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'ids' => 'nullable|string',
            'sort' => [
                'nullable',
                'string',
                'in:price_asc,price_desc,name_asc,name_desc,created_at_desc',
            ],
            'per_page' => 'nullable|integer|min:1|max:100',
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
