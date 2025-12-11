<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

final class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! $this->shouldSeedTestData()) {
            return;
        }

        Product::query()->each(
            static fn (Product $product) => $product->clearMediaCollection(Product::IMAGES_COLLECTION)
        );
        Product::query()->delete();

        $categories = Category::query()
            ->whereIn('slug', [
                'vse-svechi',
                'tonkie-svechi',
                'lyubovnye-svechi',
                'svechi-dlya-privlecheniya-deneg',
            ])
            ->get()
            ->keyBy('slug');

        $products = collect([
            [
                'name' => 'Красные тонкие свечи восковые',
                'slug' => 'krasnye-tonkie-svechi',
                'description' => trim(<<<'DESC'
Красный – начало цветового спектра и символически связывается с любыми начинаниями. Цвет ассоциируется со страстью, динамикой, опасностью, энергией и силой. Красные свечи используют в ритуалах на сексуальное влечение, любовь, замужество, а также для восстановления здоровья и физической силы.

Если вы устали или вас ждет трудная работа, зажгите красную свечу и просто посидите рядом, наблюдая за пламенем. Через некоторое время силы вернутся.

Свечи из первосортного пчелиного воска отлиты вручную, полностью прокрашены, легко гнутся, но при этом держат форму и пахнут медом. Не содержат трещин и пузырьков.
DESC),
                'price' => 30,
                'old_price' => null,
                'weight' => 60,
                'width' => 1.5,
                'height' => 20.0,
                'length' => 1.5,
                'is_new' => false,
                'is_bestseller' => true,
                'sort_order' => 10,
                'categories' => ['vse-svechi', 'tonkie-svechi'],
                'image' => database_path('seeders/products/krasnye-tonkie-svechi.jpg'),
            ],
            [
                'name' => 'Белые тонкие свечи восковые',
                'slug' => 'belye-tonkie-svechi',
                'description' => trim(<<<'DESC'
Белый цвет символизирует свет, жизненную силу и ясность. Белые магические свечи универсальны: подходят для очищения, избавления от негатива, защиты и гармонизации. Они усиливают чистоту намерений, мудрость и восприимчивость.

Свечи выполнены из отбелённого пчелиного воска, полностью прокрашены и не имеют трещин или пузырьков.
DESC),
                'price' => 50,
                'old_price' => null,
                'weight' => 60,
                'width' => 1.5,
                'height' => 20.0,
                'length' => 1.5,
                'is_new' => false,
                'is_bestseller' => false,
                'sort_order' => 20,
                'categories' => ['vse-svechi', 'tonkie-svechi'],
                'image' => database_path('seeders/products/belye-tonkie-svechi.jpg'),
            ],
            [
                'name' => 'Голубые тонкие свечи восковые',
                'slug' => 'golubye-tonkie-svechi',
                'description' => trim(<<<'DESC'
Голубой цвет свечи воплощает спокойствие, согласие, терпение и здоровье. Такие свечи помогают в благочестии и медитациях, приносят умиротворение и настраивают на духовное исцеление.

Голубые свечи хороши для медитационных практик и работы с вещими снами, помогают разобраться в себе, простить и получить прощение. Их пламя программирует любимого человека на верность, а сами свечи часто используют в спиритических сеансах.

Свечи изготовлены из отбелённого пчелиного воска, полностью прокрашены и сохраняют гибкость.
DESC),
                'price' => 50,
                'old_price' => null,
                'weight' => 60,
                'width' => 1.5,
                'height' => 20.0,
                'length' => 1.5,
                'is_new' => false,
                'is_bestseller' => false,
                'sort_order' => 30,
                'categories' => ['vse-svechi', 'tonkie-svechi'],
                'image' => database_path('seeders/products/golubye-tonkie-svechi.jpg'),
            ],
            [
                'name' => 'Черные тонкие свечи восковые',
                'slug' => 'chernye-tonkie-svechi',
                'description' => trim(<<<'DESC'
Тонкие черные восковые свечи легко отличить от парафиновых: они пластичны, гнутся в руках и обладают насыщенным медовым ароматом. Горят долго и ярко, не давая трещин и пузырьков.

Идеально подходят для сеансов таро, диагностики, обрядов и ритуалов, поглощают и разрушают отрицательные энергии. Служат инструментом защиты, работы с Миром Духов и Миром Мёртвых.

Свечи изготовлены из воска высшего сорта, вручную и с соблюдением колдовских правил, полностью прокрашены и сохраняют форму.
DESC),
                'price' => 30,
                'old_price' => null,
                'weight' => 60,
                'width' => 1.5,
                'height' => 20.0,
                'length' => 1.5,
                'is_new' => false,
                'is_bestseller' => true,
                'sort_order' => 40,
                'categories' => ['vse-svechi', 'tonkie-svechi'],
                'image' => database_path('seeders/products/chernye-tonkie-svechi.jpg'),
            ],
            [
                'name' => 'Недосягаемость тонкие цветочные свечи с вереском',
                'slug' => 'nedosyagaemost',
                'description' => trim(<<<'DESC'
Основное действие свечи — ощущение недосягаемости, неуязвимости и призрачности. Она дарует ореол притяжения, харизмы и успеха, создаёт вокруг манящую вуаль и помогает укрепить позиции в личной и профессиональной сферах.

Свеча напитана вереском — сильным магическим растением таинства, защиты, любви и финансового благополучия. Вереск зарядили в местах Силы, поэтому свеча усиливает денежные и любовные практики, помогает добиваться целей и делает владельца открытым и уверенным.
DESC),
                'price' => 70,
                'old_price' => null,
                'weight' => 55,
                'width' => 1.5,
                'height' => 20.0,
                'length' => 1.5,
                'is_new' => true,
                'is_bestseller' => false,
                'sort_order' => 50,
                'categories' => ['vse-svechi', 'tonkie-svechi', 'svechi-dlya-privlecheniya-deneg'],
                'image' => database_path('seeders/products/nedosyagaemost.jpg'),
            ],
            [
                'name' => 'Дыхание Полей тонкие цветочные свечи с полынью',
                'slug' => 'dyhanie-polej-tonkie-svechi-s-polynjyu-voskovye',
                'description' => trim(<<<'DESC'
Полынь — мистическое растение, веками используемое ведьмами в лечебных и магических практиках. Её дым очищает пространство от энергетического мусора, зла и дурного сглаза.

Свечи «Дыхание Полей» помогают снять напряжение после рабочего дня, обеспечивают духовное расслабление и сопровождают ритуалы на очищение и защиту. Они подходят как самостоятельный инструмент, так и дополнение к основным практикам.

Медитативные работы с этими свечами очищают разум и задают нужное направление событиям. Тонкая форма и комфортное время горения делают их удобными в использовании.
DESC),
                'price' => 70,
                'old_price' => null,
                'weight' => 55,
                'width' => 1.5,
                'height' => 20.0,
                'length' => 1.5,
                'is_new' => false,
                'is_bestseller' => false,
                'sort_order' => 60,
                'categories' => ['vse-svechi', 'tonkie-svechi'],
                'image' => database_path('seeders/products/dyhanie-polej.jpg'),
            ],
            [
                'name' => 'Чары любви тонкие цветочные свечи восковые',
                'slug' => 'chary-lyubvi',
                'description' => trim(<<<'DESC'
Чарующие тонкие свечи созданы для любовной магии и ритуалов, усиливающих сексуальное влечение. Они могут работать как самостоятельная программа, наполняя пространство сладострастной энергией, так и служить усилителем основного ритуала.

Пламя, почитаемое с древности, пробуждает страстные чувства. Красный воск и травы усиливают колдовскую суть прожига, дарят уверенность в себе и магический ореол привлекательности.

Роза дарит красоту и совершенство, любисток и шиповник охраняют верность, а жгучий перец поддерживает страсть между партнёрами.
DESC),
                'price' => 70,
                'old_price' => null,
                'weight' => 55,
                'width' => 1.5,
                'height' => 20.0,
                'length' => 1.5,
                'is_new' => true,
                'is_bestseller' => true,
                'sort_order' => 70,
                'categories' => ['vse-svechi', 'tonkie-svechi', 'lyubovnye-svechi'],
                'image' => database_path('seeders/products/chary-lyubvi.jpg'),
            ],
            [
                'name' => 'Календула тонкие свечи цветочные восковые',
                'slug' => 'cvetochnye-svechi-kalendula',
                'description' => trim(<<<'DESC'
Свечи с календулой улучшают настроение и стимулируют творческую энергию, усиливают прорицание и ясновидение и подходят для гармонизирующих практик.

Нежные пряные свечи из натурального воска с добавлением колдовских растений и эфирных масел мягко, но сильно действуют. Заряженные силой трав, они направляют эффект на нужную сферу, очищают и успокаивают.

Жёлтая свеча с календулой помогает восстановить физические и энергетические силы, улучшает сон и дарит «внутренний свет». Её можно прожигать после сложного дня, общения с энергетическими вампирами или проведённых ритуалов. При желании к заказу можно добавить индивидуальное поздравление или благодарность.
DESC),
                'price' => 70,
                'old_price' => null,
                'weight' => 55,
                'width' => 1.5,
                'height' => 20.0,
                'length' => 1.5,
                'is_new' => false,
                'is_bestseller' => false,
                'sort_order' => 80,
                'categories' => ['vse-svechi', 'tonkie-svechi'],
                'image' => database_path('seeders/products/kalendula.jpg'),
            ],
            [
                'name' => 'Радуга. Чакры и Очищение набор тонких восковых свечей',
                'slug' => 'raduga-chakry-i-ochishenie-nabor-tonkih-voskovyh-svechej',
                'description' => trim(<<<'DESC'
Набор из чистого пчелиного воска подходит для тех, кто развивается духовно. Его используют в универсальных практиках и ритуалах, а также для работы с чакрами — энергетическими центрами человека.

В комплект входит 120 свечей: по 10 штук красного, оранжевого, жёлтого, зелёного, голубого, синего и фиолетового цветов, а также 50 чёрных свечей.
DESC),
                'price' => 2410,
                'old_price' => null,
                'weight' => 1200,
                'width' => 12.0,
                'height' => 25.0,
                'length' => 18.0,
                'is_new' => false,
                'is_bestseller' => true,
                'sort_order' => 90,
                'categories' => ['vse-svechi', 'tonkie-svechi'],
                'image' => database_path('seeders/products/raduga-chakry-i-ochishenie.jpg'),
            ],
            [
                'name' => 'Тонкие свечи восковые набор 1000 штук',
                'slug' => 'tonkie-svechi-voskovye-nabor-1000-shtuk',
                'description' => trim(<<<'DESC'
Оптовый набор из 1000 тонких свечей ручной работы изготовлен из воска-капанца высшего сорта. Свечи полностью прокрашены, роскошно пахнут медом, пластичны и при этом держат форму.

В составе только воск и хлопковый фитиль. Можно заказать любое количество и комбинацию цветов, срок исполнения оптовых заказов — от четырёх дней.
DESC),
                'price' => 20090,
                'old_price' => null,
                'weight' => 8000,
                'width' => 25.0,
                'height' => 25.0,
                'length' => 40.0,
                'is_new' => false,
                'is_bestseller' => false,
                'sort_order' => 100,
                'categories' => ['vse-svechi', 'tonkie-svechi'],
                'image' => database_path('seeders/products/tonkie-svechi-nabor-1000.jpg'),
            ],
        ]);

        $createdProducts = collect();

        $products->each(function (array $data) use ($categories, $createdProducts): void {
            $categorySlugs = $data['categories'];
            $imagePath = $data['image'];
            unset($data['categories'], $data['image']);

            /** @var Product $product */
            $product = Product::create($data);

            $categoryIds = collect($categorySlugs)
                ->map(fn (string $slug) => $categories[$slug]->id ?? null)
                ->filter()
                ->all();

            if ($categoryIds !== []) {
                $product->categories()->attach($categoryIds);
            }

            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection(Product::IMAGES_COLLECTION, 'public');
            }

            $createdProducts->push($product);
        });

        $this->seedRelatedProducts($createdProducts);
    }

    private function seedRelatedProducts(Collection $products): void
    {
        if ($products->count() <= 1) {
            return;
        }

        $products->each(function (Product $product) use ($products): void {
            $relatedIds = $products
                ->where('id', '!=', $product->id)
                ->pluck('id')
                ->shuffle()
                ->take(3)
                ->all();

            if ($relatedIds !== []) {
                $product->related()->syncWithoutDetaching($relatedIds);
            }
        });
    }

    /**
     * Определяет, нужно ли создавать тестовые данные.
     */
    private function shouldSeedTestData(): bool
    {
        return app()->environment('local', 'development');
    }
}
