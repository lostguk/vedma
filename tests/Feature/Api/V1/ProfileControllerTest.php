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
                'address' => [
                    'country',
                    'region',
                    'city',
                    'postal_code',
                    'address',
                ],
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
}
