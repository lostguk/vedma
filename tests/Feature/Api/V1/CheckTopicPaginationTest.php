w<?php

namespace Tests\Feature\Api\V1;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CheckTopicPaginationTest extends TestCase
{
    use RefreshDatabase;

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

        // Dump the response for inspection
        $this->dumpResponse($response);
    }

    private function dumpResponse($response): void
    {
        // Write the response to a file for inspection
        file_put_contents(
            base_path('tests/Feature/Api/V1/topic_pagination_response.json'),
            json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT)
        );
    }
}
