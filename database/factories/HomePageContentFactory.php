<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\HomePageContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomePageContent>
 */
final class HomePageContentFactory extends Factory
{
    protected $model = HomePageContent::class;

    public function definition(): array
    {
        return [
            'hero_title' => 'МАГИЯ ЖИВЕТ В КАЖДОМ ИЗ НАС',
            'hero_subtitle' => 'Вопрос в том, готовы ли вы её пробудить?',
            'hero_button_label' => 'Каталог',
            'hero_button_url' => '/catalog',
            'hero_image_path' => 'home/hero.jpg',

            'hero_feature_1_text' => 'Авторские изделия, заряженные энергией',
            'hero_feature_1_image_path' => 'home/hero-feature-1.svg',

            'hero_feature_2_text' => 'Традиционные рецепты и обряды',
            'hero_feature_2_image_path' => 'home/hero-feature-2.svg',

            'hero_feature_3_text' => 'Ручная работа и натуральные ингредиенты',
            'hero_feature_3_image_path' => 'home/hero-feature-3.svg',

            'about_title' => 'НАША МАГИЯ – ВАША СИЛА',
            'about_description' => 'Мы верим в силу природы, традиционных знаний и искреннего намерения.',

            'about_trust_title' => 'Почему нам доверяют?',

            'about_trust_feature_1_title' => 'Проверенные рецепты',
            'about_trust_feature_1_image_path' => 'home/about-trust-1.svg',

            'about_trust_feature_2_title' => 'Только натуральные материалы',
            'about_trust_feature_2_image_path' => 'home/about-trust-2.svg',

            'about_trust_feature_3_title' => 'Энергетическая зарядка каждого изделия',
            'about_trust_feature_3_image_path' => 'home/about-trust-3.svg',

            'about_motto' => 'Магия в ваших руках – главное, использовать её с осознанием.',

            'about_left_image_path' => 'home/about-left.jpg',
            'about_right_image_path' => 'home/about-right.jpg',

            'stats_title' => 'Мы в цифрах',
            'stats_item_1_value' => '3600+',
            'stats_item_1_label' => 'Довольных клиентов',
            'stats_item_1_text' => 'В нашем каталоге каждый найдёт инструмент для улучшения своей жизни.',

            'stats_item_2_value' => '6',
            'stats_item_2_label' => 'Лет',
            'stats_item_2_text' => 'Изготавливаем для людей волшебные свечи.',

            'stats_item_3_value' => '500+',
            'stats_item_3_label' => 'Моделей свечей',
            'stats_item_3_text' => 'Используем только натуральный пчелиный воск, травы и эфирные масла.',

            'about_more_button_label' => 'Подробнее о нас',
            'about_more_button_url' => '/about',
        ];
    }
}
