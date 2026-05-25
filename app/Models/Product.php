<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property float $price
 * @property float|null $old_price
 * @property float $weight Вес в граммах
 * @property float|null $width Ширина в см
 * @property float|null $height Высота в см
 * @property float|null $length Длина в см
 * @property bool $is_new
 * @property bool $is_bestseller
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read array<string, float|null> $dimensions
 * @property-read array<string> $images_urls
 * @property-read string|null $main_image_url
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $related
 * @property-read int|null $related_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $relatedToProducts
 * @property-read int|null $related_to_products_count
 *
 * @method static Builder<static>|Product bestseller()
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static Builder<static>|Product new()
 * @method static Builder<static>|Product newModelQuery()
 * @method static Builder<static>|Product newQuery()
 * @method static Builder<static>|Product query()
 * @method static Builder<static>|Product search(string $search)
 * @method static Builder<static>|Product whereCreatedAt($value)
 * @method static Builder<static>|Product whereDescription($value)
 * @method static Builder<static>|Product whereHeight($value)
 * @method static Builder<static>|Product whereId($value)
 * @method static Builder<static>|Product whereIsBestseller($value)
 * @method static Builder<static>|Product whereIsNew($value)
 * @method static Builder<static>|Product whereLength($value)
 * @method static Builder<static>|Product whereName($value)
 * @method static Builder<static>|Product whereOldPrice($value)
 * @method static Builder<static>|Product wherePrice($value)
 * @method static Builder<static>|Product whereSlug($value)
 * @method static Builder<static>|Product whereUpdatedAt($value)
 * @method static Builder<static>|Product whereWeight($value)
 * @method static Builder<static>|Product whereWidth($value)
 *
 * @mixin \Eloquent
 */
final class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;

    /**
     * Название коллекции изображений продукта
     */
    public const IMAGES_COLLECTION = 'images';

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'old_price',
        'weight',
        'width',
        'height',
        'length',
        'is_new',
        'is_bestseller',
        'stock',
    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'old_price' => 'float',
        'weight' => 'float',
        'width' => 'float',
        'height' => 'float',
        'length' => 'float',
        'is_new' => 'boolean',
        'is_bestseller' => 'boolean',
        'stock' => 'integer',
    ];

    /**
     * Получить ключ маршрутизации для модели.
     */
    public function isInStock(): bool
    {
        return $this->stock === null || $this->stock > 0;
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('stock')->orWhere('stock', '>', 0);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Получить опции для генерации слага.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Получить категории продукта.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    /**
     * Получить рекомендуемые продукты.
     */
    public function related(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_product_id');
    }

    /**
     * Получить продукты, которые рекомендуют текущий продукт.
     */
    public function relatedToProducts(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_related',
            'related_product_id',
            'product_id',
        );
    }

    /**
     * Scope для новых продуктов.
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope для хитов продаж.
     */
    public function scopeBestseller(Builder $query): Builder
    {
        return $query->where('is_bestseller', true);
    }

    /**
     * Scope для поиска по имени.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Зарегистрировать медиа-коллекции.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGES_COLLECTION)
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->withResponsiveImages();
    }

    /**
     * Зарегистрировать конверсии медиа.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->fit(Fit::Contain, 900, 600)
            ->quality(90)
            ->nonQueued();
    }

    /**
     * Получить URL основного изображения продукта.
     */
    public function getMainImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::IMAGES_COLLECTION);
    }

    /**
     * Получить все URL изображений продукта.
     *
     * @return array<string>
     */
    public function getImagesUrlsAttribute(): array
    {
        return $this->getMedia(self::IMAGES_COLLECTION)
            ->map(fn ($media) => $media->getUrl())
            ->toArray();
    }

    /**
     * Получить размеры продукта в виде массива.
     *
     * @return array<string, float|null>
     */
    public function getDimensionsAttribute(): array
    {
        return [
            'weight' => $this->weight,
            'width' => $this->width,
            'height' => $this->height,
            'length' => $this->length,
        ];
    }
}
