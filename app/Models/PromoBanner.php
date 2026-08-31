<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property bool $is_active
 * @property string|null $kicker
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $discount_value
 * @property string|null $discount_caption
 * @property string|null $promo_code
 * @property string|null $button_text
 * @property string|null $button_url
 * @property \Illuminate\Support\Carbon|null $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder<static>|PromoBanner currentlyVisible()
 * @method static \Database\Factories\PromoBannerFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
final class PromoBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'kicker',
        'title',
        'subtitle',
        'discount_value',
        'discount_caption',
        'promo_code',
        'button_text',
        'button_url',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $banner): void {
            if (! $banner->is_active) {
                return;
            }

            $query = static::query()->where('is_active', true);

            if ($banner->exists) {
                $query->whereKeyNot($banner);
            }

            $query->update(['is_active' => false]);
        });
    }

    public function scopeCurrentlyVisible(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $inner) use ($now): void {
                $inner->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $inner) use ($now): void {
                $inner->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }

    public static function current(): ?self
    {
        return static::query()->currentlyVisible()->latest('id')->first();
    }
}
