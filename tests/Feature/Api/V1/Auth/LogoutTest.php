<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_logout(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson(route('api.v1.auth.logout'));

        $response->assertNoContent();
    }

    public function test_logout_unauthenticated(): void
    {
        $response = $this->postJson(route('api.v1.auth.logout'));
        $response->assertUnauthorized();
    }
}
