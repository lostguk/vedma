<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

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

    /**
     * Get unread messages count for a user (messages from admins).
     */
    public function countUnreadForUser(int $userId): int
    {
        return (int) $this->baseUnreadMessagesQuery()
            ->where('topics.user_id', $userId)
            ->where('users.is_admin', true)
            ->where(function (Builder $query) {
                $query->whereNull('topics.user_last_read_at')
                    ->orWhereColumn('messages.created_at', '>', 'topics.user_last_read_at');
            })
            ->count();
    }

    /**
     * Get unread messages count for admins (messages from users).
     */
    public function countUnreadForAdmin(): int
    {
        return (int) $this->baseUnreadMessagesQuery()
            ->where('users.is_admin', false)
            ->where(function (Builder $query) {
                $query->whereNull('topics.admin_last_read_at')
                    ->orWhereColumn('messages.created_at', '>', 'topics.admin_last_read_at');
            })
            ->count();
    }

    private function baseUnreadMessagesQuery(): Builder
    {
        return $this->model->newQuery()
            ->join('topics', 'topics.id', '=', 'messages.topic_id')
            ->join('users', 'users.id', '=', 'messages.user_id')
            ->getQuery();
    }
}
