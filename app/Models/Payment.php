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
 *
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExternalOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereRefundedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
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
