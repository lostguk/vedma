<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\V1\TopicResource;
use App\Http\Requests\Api\V1\TopicStoreRequest;
use App\Services\TopicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use App\Http\Requests\Api\V1\MessageStoreRequest;
use App\Http\Resources\Api\V1\MessageResource;
use App\Models\Topic;

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
     * @urlParam topicId integer required ID темы. Example: 1
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
    public function show(Request $request, int $topicId): JsonResponse
    {
        // First try to find the topic by ID and user ID
        $topic = $this->topicService->getUserTopic($topicId, $request->user()->id);

        // If not found, try to find the topic by ID only
        if (!$topic) {
            $topic = Topic::with(['messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }, 'messages.user', 'messages.media'])->find($topicId);

            if (!$topic) {
                return $this->errorResponse('Тема не найдена', 404);
            }

            // Check if the user is authorized to view this topic
            if (!$request->user()->is_admin && $topic->user_id !== $request->user()->id) {
                return $this->errorResponse('Тема не найдена', 404);
            }
        }

        return $this->successResponse(
            new TopicResource($topic),
            'Детали темы'
        );
    }

    /**
     * Создание новой темы
     *
     * Создает новую тему обращения от имени аутентифицированного пользователя,
     * а также первое сообщение в этой теме.
     *
     * @authenticated
     *
     * @response 201 scenario="Тема успешно создана" {
     *     "status": "success",
     *     "message": "Тема успешно создана",
     *     "data": {
     *         "id": 3,
     *         "title": "Проблема с отображением заказа",
     *         "status": "new",
     *         "status_text": "Новый",
     *         "created_at": "2023-06-16 12:00:00",
     *         "updated_at": "2023-06-16 12:00:00",
     *         "messages_count": 1,
     *         "messages": [
     *             {
     *                 "id": 3,
     *                 "content": "Здравствуйте, у меня не отображается мой последний заказ.",
     *                 "user": {
     *                     "id": 1,
     *                     "name": "Иванов Иван Иванович",
     *                     "email": "user@example.com"
     *                 },
     *                 "created_at": "2023-06-16 12:00:00",
     *                 "updated_at": "2023-06-16 12:00:00",
     *                 "attachments": [
     *                      {
     *                         "id": 2,
     *                         "file_name": "screenshot.png",
     *                         "mime_type": "image/png",
     *                         "size": 512000,
     *                         "url": "http://example.com/storage/2/screenshot.png",
     *                         "thumbnail": "http://example.com/storage/2/conversions/screenshot-thumb.jpg",
     *                         "created_at": "2023-06-16 12:00:00"
     *                      }
     *                 ]
     *             }
     *         ]
     *     }
     * }
     *
     * @response 422 scenario="Ошибка валидации" {
     *     "status": "error",
     *     "message": "The given data was invalid.",
     *     "errors": {
     *         "title": [
     *             "The title field is required."
     *         ]
     *     }
     * }
     */
    public function store(TopicStoreRequest $request): JsonResponse
    {
        $validatedData = $request->safe()->merge(['user_id' => request()->user()->id]);
        $attachments = request()->file('attachments');

        try {
            $topic = $this->topicService->createTopic($validatedData->all(), $attachments);

            return $this->successResponse(
                new TopicResource($topic->load('messages.media', 'messages.user')),
                'Тема успешно создана',
                201
            );
        } catch (Throwable $e) {
            // Log the error
            report($e);

            return $this->errorResponse('Произошла ошибка при создании темы.', 500);
        }
    }

    /**
     * Добавление сообщения в тему
     *
     * Добавляет новое сообщение от имени аутентифицированного пользователя
     * в существующую тему. Пользователь должен быть владельцем темы.
     *
     * @authenticated
     *
     * @urlParam topicId integer required ID темы. Example: 1
     *
     * @response 201 scenario="Сообщение успешно добавлено" {
     *     "status": "success",
     *     "message": "Сообщение успешно добавлено",
     *     "data": {
     *         "id": 4,
     *         "content": "Вот скриншот моей проблемы.",
     *         "user": {
     *             "id": 1,
     *             "name": "Иванов Иван Иванович",
     *             "email": "user@example.com"
     *         },
     *         "created_at": "2023-06-16 12:30:00",
     *         "updated_at": "2023-06-16 12:30:00",
     *         "attachments": [
     *              {
     *                 "id": 3,
     *                 "file_name": "screenshot-2.png",
     *                 "mime_type": "image/png",
     *                 "size": 612000,
     *                 "url": "http://example.com/storage/3/screenshot-2.png",
     *                 "thumbnail": "http://example.com/storage/3/conversions/screenshot-2-thumb.jpg",
     *                 "created_at": "2023-06-16 12:30:00"
     *              }
     *         ]
     *     }
     * }
     * @response 403 scenario="Доступ запрещен" {
     *     "status": "error",
     *     "message": "This action is unauthorized."
     * }
     * @response 404 scenario="Тема не найдена" {
     *     "status": "error",
     *     "message": "Тема не найдена или не принадлежит пользователю"
     * }
     */
    public function addMessage(MessageStoreRequest $request, int $topicId): JsonResponse
    {
        // Find the topic by ID
        $topic = Topic::find($topicId);

        if (!$topic) {
            return $this->errorResponse('Тема не найдена', 404);
        }

        // Check if the user is authorized to update this topic
        if (!$request->user()->is_admin && $topic->user_id !== $request->user()->id) {
            return $this->errorResponse('У вас нет доступа к этой теме', 403);
        }

        $validatedData = $request->safe()->merge(['user_id' => request()->user()->id]);
        $attachments = request()->file('attachments');

        try {
            $message = $this->topicService->addMessageToTopic($topic, $validatedData->all(), $attachments);

            return $this->successResponse(
                new MessageResource($message),
                'Сообщение успешно добавлено',
                201
            );
        } catch (Throwable $e) {
            report($e);

            return $this->errorResponse('Произошла ошибка при добавлении сообщения.', 500);
        }
    }
}
