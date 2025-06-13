<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\V1\TopicResource;
use App\Services\TopicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Темы и сообщения
 *
 * API для работы с темами обращений и сообщениями пользователей
 *
 * Темы обращений представляют собой диалоги между пользователем и администратором.
 * Каждая тема может содержать множество сообщений от пользователя и администратора.
 *
 * ## Структура темы
 *
 * Каждая тема содержит следующие основные поля:
 * - `id` - Уникальный идентификатор темы
 * - `title` - Название темы
 * - `status` - Статус темы (new, resolved, requires_response)
 * - `user_id` - ID пользователя, создавшего тему.
 * - `messages` - Массив сообщений в теме (если запрошены)
 *
 * ## Структура сообщения
 *
 * Каждое сообщение содержит следующие основные поля:
 * - `id` - Уникальный идентификатор сообщения
 * - `content` - Текст сообщения
 * - `user_id` - ID пользователя, отправившего сообщение.
 * - `topic_id` - ID темы, к которой относится сообщение.
 * - `attachments` - Массив вложений к сообщению (если есть)
 */
final class TopicController extends ApiController
{
    public function __construct(
        private readonly TopicService $topicService,
    ) {}

    /**
     * Получение списка тем пользователя
     *
     * Возвращает список всех тем, созданных аутентифицированным пользователем.
     *
     * @authenticated
     *
     * @queryParam page int Номер страницы. Example: 1
     * @queryParam per_page int Количество тем на страницу. Example: 15
     *
     * @response 200 scenario="Успешный запрос" {
     *     "status": "success",
     *     "message": "Список тем пользователя",
     *     "data": {
     *         "current_page": 1,
     *         "data": [
     *             {
     *                 "id": 1,
     *                 "title": "Проблема с заказом",
     *                 "status": "new",
     *                 "status_text": "Новый",
     *                 "created_at": "2023-06-15 10:30:00",
     *                 "updated_at": "2023-06-15 10:30:00",
     *                 "messages_count": 2
     *             },
     *             {
     *                 "id": 2,
     *                 "title": "Вопрос о доставке",
     *                 "status": "requires_response",
     *                 "status_text": "Требует ответа",
     *                 "created_at": "2023-06-14 15:45:00",
     *                 "updated_at": "2023-06-14 16:20:00",
     *                 "messages_count": 3
     *             }
     *         ],
     *         "first_page_url": "http://example.com/api/v1/topics?page=1",
     *         "from": 1,
     *         "last_page": 1,
     *         "last_page_url": "http://example.com/api/v1/topics?page=1",
     *         "links": [
     *             {
     *                 "url": null,
     *                 "label": "&laquo; Previous",
     *                 "active": false
     *             },
     *             {
     *                 "url": "http://example.com/api/v1/topics?page=1",
     *                 "label": "1",
     *                 "active": true
     *             },
     *             {
     *                 "url": null,
     *                 "label": "Next &raquo;",
     *                 "active": false
     *             }
     *         ],
     *         "next_page_url": null,
     *         "path": "http://example.com/api/v1/topics",
     *         "per_page": 15,
     *         "prev_page_url": null,
     *         "to": 2,
     *         "total": 2
     *     }
     * }
     * @response 401 scenario="Не авторизован" {
     *     "message": "Unauthenticated."
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = (int) $request->query('per_page', '15');
        $topics = $this->topicService->getUserTopics($user->id, $perPage);

        // Create a resource collection with pagination
        $collection = TopicResource::collection($topics);

        // Return the response with pagination metadata preserved
        return $this->successPaginatedResponse(
            $collection,
            'Список тем пользователя'
        );
    }

    /**
     * Получение темы с сообщениями
     *
     * Возвращает детальную информацию о теме, включая все сообщения в ней.
     * Тема должна принадлежать аутентифицированному пользователю.
     *
     * @authenticated
     *
     * @urlParam id integer required ID темы. Example: 7
     *
     * @response 200 scenario="Успешный запрос" {
     *     "status": "success",
     *     "message": "Детали темы",
     *     "data": {
     *         "id": 1,
     *         "title": "Проблема с заказом",
     *         "status": "new",
     *         "status_text": "Новый",
     *         "created_at": "2023-06-15 10:30:00",
     *         "updated_at": "2023-06-15 10:30:00",
     *         "messages_count": 2,
     *         "messages": [
     *             {
     *                 "id": 1,
     *                 "content": "У меня возникла проблема с последним заказом. Не получил подтверждение оплаты.",
     *                 "user": {
     *                     "id": 1,
     *                     "name": "Иванов Иван Иванович",
     *                     "email": "user@example.com"
     *                 },
     *                 "created_at": "2023-06-15 10:30:00",
     *                 "updated_at": "2023-06-15 10:30:00",
     *                 "attachments": [
     *                     {
     *                         "id": 1,
     *                         "file_name": "document.pdf",
     *                         "mime_type": "application/pdf",
     *                         "size": 1024000,
     *                         "url": "http://example.com/storage/1/document.pdf",
     *                         "thumbnail": "http://example.com/storage/1/conversions/document-thumb.jpg",
     *                         "created_at": "2023-06-15 10:30:00"
     *                     }
     *                 ]
     *             },
     *             {
     *                 "id": 2,
     *                 "content": "Здравствуйте! Проверим информацию по вашему заказу и свяжемся с вами в ближайшее время.",
     *                 "user": {
     *                     "id": 2,
     *                     "name": "Администратор",
     *                     "email": "admin@example.com"
     *                 },
     *                 "created_at": "2023-06-15 11:15:00",
     *                 "updated_at": "2023-06-15 11:15:00",
     *                 "attachments": []
     *             }
     *         ]
     *     }
     * }
     * @response 401 scenario="Не авторизован" {
     *     "message": "Unauthenticated."
     * }
     * @response 404 scenario="Тема не найдена" {
     *     "status": "error",
     *     "message": "Тема не найдена или не принадлежит пользователю"
     * }
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $topic = $this->topicService->getUserTopic($id, $user->id);

        if (! $topic) {
            return $this->errorResponse('Тема не найдена или не принадлежит пользователю', 404);
        }

        return $this->successResponse(
            new TopicResource($topic),
            'Детали темы'
        );
    }
}
