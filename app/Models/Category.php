<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as SupportCollection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $parent_id
 * @property bool $is_visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $descendants
 * @property-read int|null $descendants_count
 * @property-read string $full_path
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HomePageContent> $homePageContents
 * @property-read int|null $home_page_contents_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Category|null $parent
 *
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|Category newModelQuery()
 * @method static Builder<static>|Category newQuery()
 * @method static Builder<static>|Category query()
 * @method static Builder<static>|Category root()
 * @method static Builder<static>|Category slugUniqueInParent(string $slug, ?int $parentId = null, ?int $ignoreId = null)
 * @method static Builder<static>|Category visible()
 * @method static Builder<static>|Category whereCreatedAt($value)
 * @method static Builder<static>|Category whereDescription($value)
 * @method static Builder<static>|Category whereId($value)
 * @method static Builder<static>|Category whereIsVisible($value)
 * @method static Builder<static>|Category whereName($value)
 * @method static Builder<static>|Category whereParentId($value)
 * @method static Builder<static>|Category whereSlug($value)
 * @method static Builder<static>|Category whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class Category extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public const ICON_COLLECTION = 'icon';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_visible',
        'exclude_from_shipping',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'exclude_from_shipping' => 'boolean',
    ];

    protected $appends = [
        'full_path',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('id');
    }

    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    public function getAllDescendants(): SupportCollection
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }

        return $descendants;
    }

    /**
     * Проверяет, исключена ли категория из расчёта доставки.
     * Учитывает флаг на самой категории и рекурсивно на всех родителях.
     */
    public function isExcludedFromShipping(): bool
    {
        if ($this->exclude_from_shipping) {
            return true;
        }

        $parent = $this->parent;
        $depth = 0;

        while ($parent && $depth < 10) {
            if ($parent->exclude_from_shipping) {
                return true;
            }
            $parent = $parent->parent;
            $depth++;
        }

        return false;
    }

    public function getFullPathAttribute(): string
    {
        $path = collect([$this->slug]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->slug);
            $parent = $parent->parent;
        }

        return $path->join('/');
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id')
            ->orderBy('id');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeSlugUniqueInParent(Builder $query, string $slug, ?int $parentId = null, ?int $ignoreId = null): Builder
    {
        return $query->where('slug', $slug)
            ->where('parent_id', $parentId)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId));
    }

    /**
     * Получить главные страницы, к которым привязана категория.
     */
    public function homePageContents(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\HomePageContent::class,
            'category_home_page_content',
            'category_id',
            'home_page_content_id'
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::ICON_COLLECTION)
            ->useDisk('public')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections(self::ICON_COLLECTION);
    }
}
