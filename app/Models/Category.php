<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as SupportCollection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'sort_order',
        'is_visible',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
    ];

    protected $appends = [
        'full_path',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')
            ->singleFile()
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->performOnCollections('icon');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
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
            ->where('is_visible', true)
            ->orderBy('sort_order');
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
}
