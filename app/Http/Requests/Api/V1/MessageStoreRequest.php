<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Topic;
use Illuminate\Validation\Rules\File;

/**
 * @bodyParam content string required Текст сообщения. Example: Вот дополнительная информация по моему вопросу.
 * @bodyParam attachments file[] Массив вложений (скриншоты). Каждый файл до 2MB. Example: null
 *
 * @property-read string $content
 * @property-read array|null $attachments
 */
final class MessageStoreRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => [
                'file',
                'mimes:jpeg,jpg,png,webp,pdf',
                'max:' . (2 * 1024), // 2MB
            ],
        ];
    }

    /**
     * Define the body parameters for Scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'content' => [
                'description' => 'Текст сообщения.',
                'example' => 'Вот дополнительная информация по моему вопросу.',
            ],
            'attachments' => [
                'description' => 'Массив вложений (скриншоты). Каждый файл до 2MB.',
                'example' => null,
            ],
        ];
    }
}
