<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
