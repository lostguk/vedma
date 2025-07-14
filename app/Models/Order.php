<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property int|null $promo_code_id
 * @property float $total_price
 * @property string $status
 * @property string|null $payment_type
 * @property Carbon|null $paid_at
 * @property string|null $comment
 * @property string|null $delivery_type
 * @property float|null $delivery_price
 * @property string|null $delivery_status
 * @property array|null $delivery_data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User|null $user
 * @property-read PromoCode|null $promoCode
 * @property-read OrderItem[] $items
 */
final class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'middle_name', 'email', 'phone',
        'address', 'promo_code_id',
        'total_price', 'status', 'payment_type', 'paid_at', 'comment',
        'delivery_type', 'delivery_price', 'delivery_status', 'delivery_data',
    ];

    protected $casts = [
        'user_id' => 'int',
        'promo_code_id' => 'int',
        'total_price' => 'float',
        'delivery_price' => 'float',
        'paid_at' => 'datetime',
        'delivery_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
