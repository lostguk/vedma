<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_pages(): void
    {
        Page::truncate();
        $pages = [
            [
                'title' => 'Главная',
                'description' => 'Главная страница',
                'text' => '<p>Добро пожаловать на наш сайт!</p>',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ],
            [
                'title' => 'Оферта',
                'description' => 'Публичная оферта',
                'text' => '<p>Текст публичной оферты.</p>',
                'is_visible_in_header' => false,
                'is_visible_in_footer' => true,
            ],
        ];
        foreach ($pages as $page) {
            Page::create($page);
        }

        $response = $this->getJson('/api/v1/pages');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'title', 'description', 'text', 'is_visible_in_header', 'is_visible_in_footer'],
                ],
            ])
            ->assertJsonFragment([
                'title' => 'Главная',
                'is_visible_in_header' => true,
                'is_visible_in_footer' => true,
            ])
            ->assertJsonFragment([
                'title' => 'Оферта',
                'is_visible_in_header' => false,
                'is_visible_in_footer' => true,
            ]);
    }

    public function test_show_returns_single_page(): void
    {
        $page = Page::create([
            'title' => 'Контакты',
            'description' => 'Контактная информация',
            'text' => '<p>Свяжитесь с нами по указанным контактам.</p>',
            'is_visible_in_header' => true,
            'is_visible_in_footer' => true,
        ]);

        $response = $this->getJson('/api/v1/pages/'.$page->id);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'description' => $page->description,
                    'text' => $page->text,
                    'is_visible_in_header' => true,
                    'is_visible_in_footer' => true,
                ],
            ]);
    }

    public function test_show_returns_404_for_missing_page(): void
    {
        $response = $this->getJson('/api/v1/pages/999999');

        $response->assertNotFound()
            ->assertJson([
                'status' => 'error',
                'message' => 'Resource not found.',
            ]);
    }
}
