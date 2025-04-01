<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

final class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;

    /**
     * Название коллекции изображений продукта
     */
    public const string IMAGES_COLLECTION = 'images';

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
        'sort_order',
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
        'sort_order' => 'integer',
    ];

    /**
     * Получить ключ маршрутизации для модели.
     */
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
