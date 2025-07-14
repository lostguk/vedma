<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('api.v1.auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'status', 'message', 'data' => ['user', 'token'],
            ]);
    }

    public function test_login_with_invalid_data(): void
    {
        $response = $this->postJson(route('api.v1.auth.login'), [
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test2@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('api.v1.auth.login'), [
            'email' => 'test2@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_with_nonexistent_email(): void
    {
        $response = $this->postJson(route('api.v1.auth.login'), [
            'email' => 'notfound@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
