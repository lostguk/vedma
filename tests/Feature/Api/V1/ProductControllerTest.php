<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест успешного получения продукта по slug
     */
    public function test_can_get_product_by_slug(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->slug}");

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'description',
                    'price',
                    'dimensions' => [
                        'width',
                        'height',
                        'depth',
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
    public function test_returns_404_when_product_not_found(): void
    {
        $response = $this->getJson('/api/v1/products/non-existent-product');

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
    public function test_product_response_has_correct_data_types(): void
    {
        $product = Product::factory()->create([
            'price' => 99.99,
        ]);

        $response = $this->getJson("/api/v1/products/{$product->slug}");

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'price',
                ],
            ])
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', $product->name)
            ->assertJsonPath('data.slug', $product->slug)
            ->assertJsonPath('data.price', 99.99);
    }

    /**
     * Тест проверки загрузки связанных данных (категории и рекомендуемые товары)
     */
    public function test_product_includes_related_data(): void
    {
        $product = Product::factory()
            ->hasCategories(2)
            ->hasRelated(3)
            ->create();

        $response = $this->getJson("/api/v1/products/{$product->slug}");

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
}
