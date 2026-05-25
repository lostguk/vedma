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
 * @property int $total_price
 * @property int|null $total_price_without_discount
 * @property int|null $total_price_with_discount
 * @property string $status
 * @property string|null $payment_type
 * @property Carbon|null $paid_at
 * @property string|null $comment
 * @property string|null $delivery_type
 * @property int|null $delivery_price
 * @property string|null $delivery_status
 * @property array|null $delivery_data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User|null $user
 * @property-read PromoCode|null $promoCode
 * @property-read OrderItem[] $items
 * @property-read Payment[] $payments
 * @property-read int|null $items_count
 * @property-read int|null $payments_count
 *
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePromoCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPriceWithDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPriceWithoutDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'middle_name', 'email', 'phone',
        'address', 'promo_code_id',
        'total_price', 'total_price_without_discount', 'total_price_with_discount',
        'status', 'payment_type', 'paid_at', 'comment',
        'delivery_type', 'delivery_price', 'delivery_status', 'delivery_data',
    ];

    protected $casts = [
        'user_id' => 'int',
        'promo_code_id' => 'int',
        'total_price' => 'int',
        'total_price_without_discount' => 'int',
        'total_price_with_discount' => 'int',
        'delivery_price' => 'int',
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
