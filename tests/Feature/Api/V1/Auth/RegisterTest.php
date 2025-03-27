<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private array $validData = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'middle_name' => 'Middle',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '+79001234567',
        'address' => 'Test Address',
    ];

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->postJson(route('api.v1.auth.register'), $this->validData);

        $response->assertStatus(200)
            ->assertJsonStructure([
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
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->validData['email'],
            'first_name' => $this->validData['first_name'],
            'last_name' => $this->validData['last_name'],
            'middle_name' => $this->validData['middle_name'],
        ]);
    }

    public function test_user_cannot_register_with_existing_email(): void
    {
        User::factory()->create(['email' => $this->validData['email']]);

        $response = $this->postJson(route('api.v1.auth.register'), $this->validData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_without_required_fields(): void
    {
        $response = $this->postJson(route('api.v1.auth.register'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'middle_name',
                'email',
                'password',
            ]);
    }

    public function test_user_cannot_register_with_invalid_email(): void
    {
        $data = $this->validData;
        $data['email'] = 'invalid-email';

        $response = $this->postJson(route('api.v1.auth.register'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_with_short_password(): void
    {
        $data = $this->validData;
        $data['password'] = 'short';
        $data['password_confirmation'] = 'short';

        $response = $this->postJson(route('api.v1.auth.register'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_cannot_register_with_mismatched_passwords(): void
    {
        $data = $this->validData;
        $data['password_confirmation'] = 'different_password';

        $response = $this->postJson(route('api.v1.auth.register'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_cannot_register_with_invalid_phone_format(): void
    {
        $data = $this->validData;
        $data['phone'] = 'invalid-phone';

        $response = $this->postJson(route('api.v1.auth.register'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_user_can_register_without_optional_fields(): void
    {
        $data = $this->validData;
        unset($data['phone'], $data['address']);

        $response = $this->postJson(route('api.v1.auth.register'), $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
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
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
        ]);
    }
}
