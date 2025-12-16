<?php

namespace Database\Seeders;

use App\Models\Page;
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
                'slug' => 'glavnaya',
                'description' => 'Главная страница',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Каталог',
                'slug' => 'katalog',
                'description' => 'Каталог товаров',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Доставка и оплата',
                'slug' => 'dostavka-i-oplata',
                'description' => '',
                'text' => '
                <h2>Доставка</h2>
                <p>Мы доставляем наши по всей России. Стоимость доставки зависит от региона.
Когда оформите заказ, наш менеджер свяжется с вами и сообщит стоимость, а также уточнит детали доставки.</p>
                <h4>Почта России</h4>
                <p>От 5 до 30 дней после передачи посылки в отделение почты.
Если сумма заказа больше 70 000 ₽, доставка бесплатно. Если меньше — от 400 до 2500 ₽, зависит от региона.</p>
                <h2>Оплата</h2>
                <p>У нас подключен интернет-эквайринг. С ним можно оплачивать картой или через Систему быстрых платежей.
</p>
                ',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Обмен и возврат',
                'slug' => 'obmen-i-vozvrat',
                'description' => '',
                'text' => '
<h2>Возврат</h2>
<p>Вы можете вернуть товар в течение 14 дней после получения на пункте выдачи, при условии сохранения товарного вида и упаковки. Для оформления возврата необходимо связаться с нами в WhatsApp. Доставку до нашего склада вы оплачиваете самостоятельно. Возврат денежных средств будет произведен после предоставления квитанции отправления товара.</p>
<h2>Если нашли брак</h2>
<p>Если вы нашли брак или скрытый дефект — можете вернуть товар, даже если прошло больше 14 дней после получения товара. Доставку на наш склад оплатим мы.<br /> Для возврата напишите нам в WhatsApp и приложите фото, которые подтверждают брак.
<br />В течение дня наш сотрудник примет обращение и расскажет, что делать дальше.</p>
',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'description' => '',
                'text' => '
                <h2>Адресс</h2>
                <p>Краснодарский край, Северский р-он, пгт. Афипский,
                <br /> Красноармейская д.72 </p>
                <h2>ИП</h2>
                <p>ИП Лушникова Александра Петровна
               <br />ИНН 231108788087</p>
               <h2>Контакты</h2>
               <p>8 (960) 492-16-69 <br /> WhatsApp</p>

                ',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Оферта',
                'slug' => 'oferta',
                'description' => 'Публичная оферта',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => false,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Политика конфиденциальности',
                'slug' => 'politika-konfidentsialnosti',
                'description' => 'Политика конфиденциальности',
                'text' => '<p>'.$faker->text(200).'</p><h2>Заголовок</h2><p>'.$faker->text(200).'</p>',
                'is_visible_in_header' => false,
                'is_visible_in_footer' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
