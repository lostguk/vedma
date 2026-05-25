<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_verifies_email_if_unverified(): void
    {
        $plainToken = 'reset-token-123';
        $user = User::factory()->unverified()->create([
            'email' => 'unverified-reset@example.com',
        ]);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($plainToken),
                'created_at' => now(),
            ]
        );

        $response = $this->postJson(route('api.v1.auth.reset-password'), [
            'email' => $user->email,
            'token' => $plainToken,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.message', 'Пароль успешно изменён.');

        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    }
}
