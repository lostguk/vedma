<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
