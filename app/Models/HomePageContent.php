<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $hero_title
 * @property string|null $hero_subtitle
 * @property string|null $hero_button_label
 * @property string|null $hero_button_url
 * @property string|null $hero_image_path
 * @property string|null $hero_feature_1_text
 * @property string|null $hero_feature_1_image_path
 * @property string|null $hero_feature_2_text
 * @property string|null $hero_feature_2_image_path
 * @property string|null $hero_feature_3_text
 * @property string|null $hero_feature_3_image_path
 * @property string $about_title
 * @property string|null $about_description
 * @property string|null $about_trust_title
 * @property string|null $about_trust_feature_1_title
 * @property string|null $about_trust_feature_1_image_path
 * @property string|null $about_trust_feature_2_title
 * @property string|null $about_trust_feature_2_image_path
 * @property string|null $about_trust_feature_3_title
 * @property string|null $about_trust_feature_3_image_path
 * @property string|null $about_motto
 * @property string|null $about_left_image_path
 * @property string|null $about_right_image_path
 * @property string|null $stats_title
 * @property string|null $stats_item_1_value
 * @property string|null $stats_item_1_label
 * @property string|null $stats_item_1_text
 * @property string|null $stats_item_2_value
 * @property string|null $stats_item_2_label
 * @property string|null $stats_item_2_text
 * @property string|null $stats_item_3_value
 * @property string|null $stats_item_3_label
 * @property string|null $stats_item_3_text
 * @property string|null $about_more_button_label
 * @property string|null $about_more_button_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 *
 * @method static \Database\Factories\HomePageContentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutLeftImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutMoreButtonLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutMoreButtonUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutMotto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutRightImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutTrustFeature1ImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutTrustFeature1Title($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutTrustFeature2ImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutTrustFeature2Title($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutTrustFeature3ImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutTrustFeature3Title($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereAboutTrustTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroButtonLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroButtonUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroFeature1ImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroFeature1Text($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroFeature2ImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroFeature2Text($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroFeature3ImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroFeature3Text($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereHeroTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem1Label($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem1Text($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem1Value($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem2Label($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem2Text($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem2Value($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem3Label($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem3Text($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsItem3Value($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereStatsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HomePageContent whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class HomePageContent extends Model
{
    use HasFactory;

    protected $table = 'home_page_contents';

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_button_label',
        'hero_button_url',
        'hero_image_path',

        'hero_feature_1_text',
        'hero_feature_1_image_path',
        'hero_feature_2_text',
        'hero_feature_2_image_path',
        'hero_feature_3_text',
        'hero_feature_3_image_path',

        'about_title',
        'about_description',

        'about_trust_title',
        'about_trust_feature_1_title',
        'about_trust_feature_1_image_path',
        'about_trust_feature_2_title',
        'about_trust_feature_2_image_path',
        'about_trust_feature_3_title',
        'about_trust_feature_3_image_path',

        'about_motto',
        'about_left_image_path',
        'about_right_image_path',

        'stats_title',
        'stats_item_1_value',
        'stats_item_1_label',
        'stats_item_1_text',
        'stats_item_2_value',
        'stats_item_2_label',
        'stats_item_2_text',
        'stats_item_3_value',
        'stats_item_3_label',
        'stats_item_3_text',

        'about_more_button_label',
        'about_more_button_url',
    ];

    protected function casts(): array
    {
        return [];
    }

    /**
     * Получить категории для главной страницы.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_home_page_content', 'home_page_content_id', 'category_id')
            ->orderBy('categories.id');
    }
}
