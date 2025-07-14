<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rules\File;

/**
 * @bodyParam title string required Заголовок темы. Example: Проблема с отображением заказа
 * @bodyParam content string required Текст первого сообщения. Example: Здравствуйте, у меня не отображается мой последний заказ.
 * @bodyParam attachments file[] Массив вложений (скриншоты). Максимум 5 файлов, каждый до 2MB.
 *
 * @property-read string $title
 * @property-read string $content
 * @property-read array|null $attachments
 */
final class TopicStoreRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => [
                'file',
                'mimes:jpeg,jpg,png,webp,pdf',
//                'max:' . (2 * 1024), // 2MB
            ],
        ];
    }

    /**
     * @bodyParam title string required Заголовок темы. Example: Проблема с отображением заказа
     * @bodyParam content string required Текст первого сообщения. Example: Здравствуйте, у меня не отображается мой последний заказ.
     * @bodyParam attachments file[] Массив вложений (скриншоты). Максимум 5 файлов, каждый до 2MB.
     *
     * @property-read string $title
     * @property-read string $content
     * @property-read array|null $attachments
     */

    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Заголовок темы.',
                'example' => 'Проблема с отображением заказа',
            ],
            'content' => [
                'description' => 'Текст первого сообщения.',
                'example' => 'Здравствуйте, у меня не отображается мой последний заказ.',
            ],
            'attachments' => [
                'description' => 'Массив вложений (скриншоты). Максимум 5 файлов, каждый до 2MB.',
                'example' => null,
            ],
        ];
    }
}
