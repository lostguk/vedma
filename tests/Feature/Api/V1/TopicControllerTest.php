<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\Message;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class TopicControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_topics_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create topics for the user
        Topic::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // Create topics for another user
        $otherUser = User::factory()->create();
        Topic::factory()->count(2)->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->getJson(route('user.topics.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'status',
                        'status_text',
                        'created_at',
                        'updated_at',
                        'messages_count',
                    ],
                ],
                'meta' => [
                    'current_page',
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                ],
            ],
        ]);

        // Check that only the user's topics are returned
        $response->assertJsonCount(3, 'data.data');
    }

    public function test_get_user_topics_unauthorized(): void
    {
        $response = $this->getJson('/api/v1/topics');
        $response->assertUnauthorized();
    }

    public function test_get_user_topic_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a topic for the user
        $topic = Topic::factory()->create([
            'user_id' => $user->id,
        ]);

        // Create messages for the topic
        Message::factory()->count(3)->create([
            'user_id' => $user->id,
            'topic_id' => $topic->id,
        ]);

        $response = $this->getJson("/api/v1/topics/{$topic->id}");

        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'title',
                'status',
                'status_text',
                'created_at',
                'updated_at',
                'messages_count',
                'messages' => [
                    '*' => [
                        'id',
                        'content',
                        'user',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);

        // Check that the correct topic is returned
        $response->assertJsonPath('data.id', $topic->id);
        // Check that all messages are returned
        $response->assertJsonCount(3, 'data.messages');
    }

    public function test_get_user_topic_not_found(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/topics/999');

        $response->assertNotFound();
        $response->assertJsonPath('status', 'error');
    }

    public function test_get_user_topic_unauthorized(): void
    {
        $topic = Topic::factory()->create();

        $response = $this->getJson("/api/v1/topics/{$topic->id}");
        $response->assertUnauthorized();
    }

    public function test_get_user_topic_forbidden(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a topic for another user
        $otherUser = User::factory()->create();
        $topic = Topic::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->getJson("/api/v1/topics/{$topic->id}");

        $response->assertNotFound();
        $response->assertJsonPath('status', 'error');
    }

    public function test_topics_response_includes_pagination(): void
    {
        // Create a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create topics for the user
        Topic::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        // Make the request
        $response = $this->getJson('/api/v1/topics?page=1&per_page=2');

        // Check the response structure
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'data',
                'meta' => [
                    'current_page',
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                ],
            ],
        ]);
    }
}
