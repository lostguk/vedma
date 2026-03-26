<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $status
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $user_last_read_at
 * @property \Illuminate\Support\Carbon|null $admin_last_read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\TopicFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereAdminLastReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereUserLastReadAt($value)
 *
 * @mixin \Eloquent
 */
final class Topic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'status',
        'user_id',
        'user_last_read_at',
        'admin_last_read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'user_last_read_at' => 'datetime',
        'admin_last_read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the topic.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the messages for the topic.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getUnreadMessagesCountFor(User $user): int
    {
        $query = $this->messages()->whereHas('user', function ($query) use ($user) {
            $query->where('is_admin', ! $user->is_admin);
        });

        $lastReadAt = $user->is_admin ? $this->admin_last_read_at : $this->user_last_read_at;

        if ($lastReadAt) {
            $query->where('created_at', '>', $lastReadAt);
        }

        return $query->count();
    }
}
