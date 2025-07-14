<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест успешного получения продукта по slug
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_get_product_by_slug(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('api.v1.products.show', ['slug' => $product->slug]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'description',
                    'price',
                    'old_price',
                    'dimensions' => [
                        'width',
                        'height',
                        'length',
                        'weight',
                    ],
                    'categories',
                    'related',
                    'images_urls',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * Тест получения 404 ошибки при запросе несуществующего продукта
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_returns_404_when_product_not_found(): void
    {
        $response = $this->getJson(route('api.v1.products.show', ['slug' => 'non-existent-product']));

        $response
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ])
            ->assertJson([
                'message' => 'Запрашиваемый ресурс не найден',
            ]);
    }

    /**
     * Тест проверки правильного формата данных в ответе
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_product_response_has_correct_data_types(): void
    {
        $product = Product::factory()->create([
            'price' => 99.99,
        ]);

        $response = $this->getJson(route('api.v1.products.show', ['slug' => $product->slug]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'price',
                    'old_price',
                ],
            ])
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', $product->name)
            ->assertJsonPath('data.slug', $product->slug)
            ->assertJsonPath('data.price', 99.99)
            ->assertJsonPath('data.old_price', $product->old_price);
    }

    /**
     * Тест проверки загрузки связанных данных (категории и рекомендуемые товары)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_product_includes_related_data(): void
    {
        $product = Product::factory()
            ->hasCategories(2)
            ->hasRelated(3)
            ->create();

        $response = $this->getJson(route('api.v1.products.show', ['slug' => $product->slug]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'categories' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                        ],
                    ],
                    'related' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                        ],
                    ],
                ],
            ])
            ->assertJsonCount(2, 'data.categories')
            ->assertJsonCount(3, 'data.related');
    }

    // --- New Tests for Index Method ---

    /**
     * Тест получения списка продуктов с пагинацией по умолчанию.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_paginated_products_without_params(): void
    {
        Product::factory(20)->create();

        $response = $this->getJson(route('api.v1.products.index'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'description',
                        'price',
                        'old_price',
                        'dimensions',
                        'categories',
                        'related',
                        'images_urls',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ])
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('meta.per_page', 15)
            ->assertJsonPath('meta.total', 20);
    }

    /**
     * Тест получения списка продуктов с фильтрами, сортировкой и кастомной пагинацией.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_filtered_sorted_paginated_products_with_params(): void
    {
        $category1 = Category::factory()->create(['slug' => 'category-a']);
        $category2 = Category::factory()->create(['slug' => 'category-b']);

        $product1 = Product::factory()->create(['name' => 'Test Apple', 'price' => 150]);
        $product1->categories()->attach($category1->id);

        $product2 = Product::factory()->create(['name' => 'Test Banana', 'price' => 120]);
        $product2->categories()->attach($category2->id);

        $product3 = Product::factory()->create(['name' => 'Test Apricot', 'price' => 80]);
        $product3->categories()->attach($category1->id);

        $product4 = Product::factory()->create(['name' => 'Test Avocado', 'price' => 250]);
        $product4->categories()->attach($category1->id);

        $params = [
            'category' => 'category-a',
            'price_from' => 100,
            'price_to' => 200,
            'sort' => 'price_asc',
            'per_page' => 5,
        ];

        $response = $this->getJson(route('api.v1.products.index', $params));

        $response
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $product1->id)
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('meta.per_page', 5);

        // Test name sorting (desc)
        $params = [
            'category' => 'category-a',
            'sort' => 'name_desc',
            'per_page' => 5,
        ];
        $responseNameDesc = $this->getJson(route('api.v1.products.index', $params));
        $responseNameDesc->assertOk();
        $responseNameDesc->assertJsonPath('data.0.id', $product4->id);
        $responseNameDesc->assertJsonPath('data.1.id', $product3->id);
        $responseNameDesc->assertJsonPath('data.2.id', $product1->id);
        $responseNameDesc->assertJsonCount(3, 'data');
    }
}
