<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Topic;
use App\Repositories\MessageRepository;
use App\Repositories\TopicRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}
