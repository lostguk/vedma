<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Message;
use App\Models\Topic;
use App\Repositories\MessageRepository;
use App\Repositories\TopicRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class TopicService
{
    public function __construct(
        private TopicRepository $topicRepository,
        private MessageRepository $messageRepository,
    ) {}

    /**
     * Get all topics for a user
     */
    public function getUserTopics(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->topicRepository->getByUserId($userId, $perPage);
    }

    /**
     * Get a specific topic with messages for a user
     */
    public function getUserTopic(int $topicId, int $userId): ?Topic
    {
        return $this->topicRepository->getWithMessagesById($topicId, $userId);
    }

    /**
     * Create a new topic with the first message and attachments.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, UploadedFile>|null  $attachments
     *
     * @throws Throwable
     */
    public function createTopic(array $data, ?array $attachments): Topic
    {
        return DB::transaction(function () use ($data, $attachments) {
            $topic = $this->topicRepository->create([
                'title' => $data['title'],
                'user_id' => $data['user_id'],
                'status' => 'new',
            ]);

            $message = $this->messageRepository->create([
                'content' => $data['content'],
                'user_id' => $data['user_id'],
                'topic_id' => $topic->id,
            ]);

            if (! empty($attachments)) {
                foreach ($attachments as $file) {
                    $message->addMedia($file)->toMediaCollection(Message::ATTACHMENTS_COLLECTION);
                }
            }

            return $topic;
        });
    }

    /**
     * Add a message to an existing topic.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, UploadedFile>|null  $attachments
     */
    public function addMessageToTopic(Topic $topic, array $data, ?array $attachments): Message
    {
        $message = $this->messageRepository->create([
            'content' => $data['content'],
            'user_id' => $data['user_id'],
            'topic_id' => $topic->id,
        ]);

        if (! empty($attachments)) {
            foreach ($attachments as $file) {
                $message->addMedia($file)->toMediaCollection(Message::ATTACHMENTS_COLLECTION);
            }
        }

        // Загружаем вложения, чтобы они были доступны в ресурсе
        $message->load('media');

        return $message;
    }
}
