<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/profile');



        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'first_name',
                'last_name',
                'middle_name',
                'full_name',
                'email',
                'phone',
                'address',
                'email_verified',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_profile_unauthorized(): void
    {
        $response = $this->getJson('/api/v1/profile');
        $response->assertUnauthorized();
    }

    public function test_update_profile_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'first_name' => 'Петр',
            'last_name' => 'Петров',
            'middle_name' => 'Петрович',
            'email' => 'petr@example.com',
            'phone' => '+79998887766',
            'address' => 'ул. Новая, д. 2',
        ];

        $response = $this->patchJson('/api/v1/profile', $payload);
        $response->assertOk();
        $response->assertJsonPath('data.first_name', 'Петр');
        $response->assertJsonPath('data.email', 'petr@example.com');
    }

    public function test_update_profile_email_validation(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $other = User::factory()->create(['email' => 'other@example.com']);

        $payload = [
            'first_name' => 'Петр',
            'last_name' => 'Петров',
            'email' => 'other@example.com', // уже занят
        ];

        $response = $this->patchJson('/api/v1/profile', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_update_profile_unauthorized(): void
    {
        $payload = [
            'first_name' => 'Петр',
            'last_name' => 'Петров',
            'email' => 'petr@example.com',
        ];
        $response = $this->patchJson('/api/v1/profile', $payload);
        $response->assertUnauthorized();
    }
}
