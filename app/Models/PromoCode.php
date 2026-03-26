<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $discount_type
 * @property float $discount_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 *
 * @method static \Database\Factories\PromoCodeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class PromoCode extends Model
{
    /** @use HasFactory<\Database\Factories\PromoCodeFactory> */
    use HasFactory;

    /** @var array<int, string> */
    protected $fillable = [
        'code',
        'start_date',
        'end_date',
        'discount_type',
        'discount_value',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_value' => 'float',
    ];

    /**
     * Категории, на которые действует промокод
     */
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'promo_code_category');
    }
}
