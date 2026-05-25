<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class DadataAddressSuggestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => ['required', 'string', 'min:3', 'max:300'],
            'count' => ['nullable', 'integer', 'gte:1', 'lte:20'],
            'language' => ['nullable', 'string', 'in:ru,en'],
            'division' => ['nullable', 'string', 'in:administrative,municipal'],
        ];
    }

    public function messages(): array
    {
        return [
            'query.required' => 'Текст запроса обязателен.',
            'query.string' => 'Текст запроса должен быть строкой.',
            'query.min' => 'Текст запроса слишком короткий.',
            'query.max' => 'Текст запроса слишком длинный.',
            'count.integer' => 'Количество подсказок должно быть числом.',
            'count.gte' => 'Количество подсказок должно быть не меньше 1.',
            'count.lte' => 'Количество подсказок должно быть не больше 20.',
            'language.in' => 'Язык подсказок должен быть ru или en.',
            'division.in' => 'Разделение должно быть administrative или municipal.',
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
            'query' => [
                'description' => 'Фрагмент адреса или полный адрес для подсказок.',
                'type' => 'string',
                'example' => 'москва хабар',
            ],
            'count' => [
                'description' => 'Количество подсказок (1-20).',
                'type' => 'integer',
                'example' => 10,
            ],
            'language' => [
                'description' => 'Язык ответа DaData (ru или en).',
                'type' => 'string',
                'example' => 'ru',
            ],
            'division' => [
                'description' => 'Тип деления адресов (administrative или municipal).',
                'type' => 'string',
                'example' => 'administrative',
            ],
        ];
    }
}
