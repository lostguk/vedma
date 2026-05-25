<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_can_get_list_of_categories(): void
    {
        // Arrange
        $rootCategory = Category::factory()->create();
        $childCategory = Category::factory()->create(['parent_id' => $rootCategory->id]);
        $anotherCategory = Category::factory()->create();

        // Act
        $response = $this->getJson(route('api.v1.categories.index'));

        // Assert
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'description',
                        'icon',
                        'parent_id',
                        'is_visible',
                        'children',
                    ],
                ],
            ]);
    }

    public function test_can_get_empty_categories_list(): void
    {
        // Act
        $response = $this->getJson(route('api.v1.categories.index'));

        // Assert
        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonStructure(['data']);
    }

    public function test_only_visible_categories_are_shown_by_default(): void
    {
        // Arrange
        $visibleCategory = Category::factory()->create(['is_visible' => true]);
        $hiddenCategory = Category::factory()->create(['is_visible' => false]);

        // Act
        $response = $this->getJson(route('api.v1.categories.index'));

        // Assert
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $visibleCategory->id);
    }

    public function test_can_show_hidden_categories_with_parameter(): void
    {
        // Arrange
        $visibleCategory = Category::factory()->create(['is_visible' => true]);
        $hiddenCategory = Category::factory()->create(['is_visible' => false]);

        // Act
        $response = $this->getJson(route('api.v1.categories.index', ['show_hidden' => true]));

        // Assert
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $visibleCategory->id)
            ->assertJsonPath('data.1.id', $hiddenCategory->id);
    }

    public function test_can_get_category_by_slug(): void
    {
        // Arrange
        $category = Category::factory()->create();
        $childCategory = Category::factory()->create(['parent_id' => $category->id]);

        // Act
        $response = $this->getJson(route('api.v1.categories.show', $category->slug));

        // Assert
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'description',
                    'icon',
                    'parent_id',
                    'children',
                ],
            ])
            ->assertJsonPath('data.id', $category->id)
            ->assertJsonPath('data.children.0.id', $childCategory->id);
    }

    public function test_returns_404_for_non_existent_category(): void
    {
        // Act
        $response = $this->getJson(route('api.v1.categories.show', 'non-existent-slug'));

        // Assert
        $response->assertNotFound();
    }

    public function test_category_with_icon_returns_correct_url(): void
    {
        // Arrange
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->image('icon.jpg');
        $category->addMedia($file)->toMediaCollection('icon');

        // Act
        $response = $this->getJson(route('api.v1.categories.show', $category->slug));

        // Assert
        $response->assertOk()
            ->assertJsonPath('data.icon', fn (string $icon) => str_contains($icon, '/storage/'));
    }

    public function test_root_categories_are_sorted_by_id(): void
    {
        $firstCategory = Category::factory()->create();
        $secondCategory = Category::factory()->create();
        $thirdCategory = Category::factory()->create();

        $response = $this->getJson(route('api.v1.categories.index'));

        $response->assertOk()
            ->assertJsonPath('data.0.id', $firstCategory->id)
            ->assertJsonPath('data.1.id', $secondCategory->id)
            ->assertJsonPath('data.2.id', $thirdCategory->id);
    }
}
