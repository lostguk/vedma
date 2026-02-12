<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $public_id
 * @property int $order_id
 * @property string $provider
 * @property string $status
 * @property int $amount
 * @property string $currency
 * @property string|null $external_order_id
 * @property string|null $payment_url
 * @property array|null $payload
 * @property string|null $error_message
 * @property Carbon|null $paid_at
 * @property Carbon|null $refunded_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Order $order
 */
final class Payment extends Model
{
    use HasFactory;

    public const STATUS_CREATED = 'created';

    public const STATUS_REGISTERED = 'registered';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'public_id',
        'order_id',
        'provider',
        'status',
        'amount',
        'currency',
        'external_order_id',
        'payment_url',
        'payload',
        'error_message',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'order_id' => 'int',
        'amount' => 'int',
        'payload' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        self::creating(function (self $payment): void {
            if (empty($payment->public_id)) {
                $payment->public_id = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
