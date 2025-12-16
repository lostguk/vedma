<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Services\HomePageContentService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Models\HomePageContent */
final class HomePageContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $heroImage = $this->hero_image_path ? Storage::url($this->hero_image_path) : null;

        // Temporary commented
        //        $heroFeature1Image = $this->hero_feature_1_image_path
        //            ? Storage::url($this->hero_feature_1_image_path)
        //            : null;
        //        $heroFeature2Image = $this->hero_feature_2_image_path
        //            ? Storage::url($this->hero_feature_2_image_path)
        //            : null;
        //        $heroFeature3Image = $this->hero_feature_3_image_path
        //            ? Storage::url($this->hero_feature_3_image_path)
        //            : null;

        $trust1Image = $this->about_trust_feature_1_image_path
            ? Storage::url($this->about_trust_feature_1_image_path)
            : null;
        $trust2Image = $this->about_trust_feature_2_image_path
            ? Storage::url($this->about_trust_feature_2_image_path)
            : null;
        $trust3Image = $this->about_trust_feature_3_image_path
            ? Storage::url($this->about_trust_feature_3_image_path)
            : null;

        $aboutLeftImage = $this->about_left_image_path ? Storage::url($this->about_left_image_path) : null;
        $aboutRightImage = $this->about_right_image_path ? Storage::url($this->about_right_image_path) : null;

        // Получаем товары из категорий рекурсивно
        $categories = collect($this->categories ?? []);
        $products = collect();

        if ($categories->isNotEmpty()) {
            $service = app(HomePageContentService::class);
            $products = $service->getProductsFromCategories($categories);
        }

        return [
            'hero' => [
                'title' => $this->hero_title,
                'subtitle' => $this->hero_subtitle,
                'button' => [
                    'label' => $this->hero_button_label,
                    'url' => $this->hero_button_url,
                ],
                'image' => $heroImage,
                'features' => [
                    [
                        'text' => $this->hero_feature_1_text,
                        //                        'icon' => $heroFeature1Image,
                    ],
                    [
                        'text' => $this->hero_feature_2_text,
                        //                        'icon' => $heroFeature2Image,
                    ],
                    [
                        'text' => $this->hero_feature_3_text,
                        //                        'icon' => $heroFeature3Image,
                    ],
                ],
            ],
            'about' => [
                'title' => $this->about_title,
                'description' => $this->about_description,
                'trust' => [
                    'title' => $this->about_trust_title,
                    'items' => [
                        [
                            'title' => $this->about_trust_feature_1_title,
                            'image' => $trust1Image,
                        ],
                        [
                            'title' => $this->about_trust_feature_2_title,
                            'image' => $trust2Image,
                        ],
                        [
                            'title' => $this->about_trust_feature_3_title,
                            'image' => $trust3Image,
                        ],
                    ],
                ],
                'motto' => $this->about_motto,
                'images' => [
                    'left' => $aboutLeftImage,
                    'right' => $aboutRightImage,
                ],
                'stats' => [
                    'title' => $this->stats_title,
                    'items' => [
                        [
                            'value' => $this->stats_item_1_value,
                            'label' => $this->stats_item_1_label,
                            'text' => $this->stats_item_1_text,
                        ],
                        [
                            'value' => $this->stats_item_2_value,
                            'label' => $this->stats_item_2_label,
                            'text' => $this->stats_item_2_text,
                        ],
                        [
                            'value' => $this->stats_item_3_value,
                            'label' => $this->stats_item_3_label,
                            'text' => $this->stats_item_3_text,
                        ],
                    ],
                ],
                'moreButton' => [
                    'label' => $this->about_more_button_label,
                    'url' => $this->about_more_button_url,
                ],
            ],
            'categories' => CategoryResource::collection($categories),
            'products' => ProductResource::collection($products),
        ];
    }
}
