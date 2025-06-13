<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Topic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class TopicRepository extends BaseRepository
{
    public function __construct(Topic $model)
    {
        parent::__construct($model);
    }

    /**
     * Get topics by user ID
     */
    public function getByUserId(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->withCount('messages')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get a topic with messages by ID and user ID
     */
    public function getWithMessagesById(int $topicId, int $userId): ?Topic
    {
        return $this->model
            ->with(['messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }, 'messages.user', 'messages.media'])
            ->where('id', $topicId)
            ->where('user_id', $userId)
            ->first();
    }
}
