<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

final class MessageRepository extends BaseRepository
{
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }

    /**
     * Get messages by topic ID
     */
    public function getByTopicId(int $topicId): Collection
    {
        return $this->model->where('topic_id', $topicId)
            ->with(['user', 'media'])
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
