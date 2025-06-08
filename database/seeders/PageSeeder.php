<?php

namespace Database\Seeders;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create('ru_RU');
        $pages = [
            [
                'title' => 'Главная',
                'description' => 'Главная страница',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Каталог',
                'description' => 'Каталог товаров',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Доставка и оплата',
                'description' => 'Информация о доставке и оплате',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Обмен и возврат',
                'description' => 'Обмен и возврат товаров',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Контакты',
                'description' => 'Контактная информация',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Оферта',
                'description' => 'Публичная оферта',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => false,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Политика конфиденциальности',
                'description' => 'Политика конфиденциальности',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => false,
                'is_visible_in_footer' => true,
            ],
        ];

        foreach ($pages as $page) {
            \App\Models\Page::create($page);
        }
    }
}
