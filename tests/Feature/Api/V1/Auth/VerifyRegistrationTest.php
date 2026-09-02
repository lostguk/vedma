<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

final class VerifyRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function signedUrl(User $user, bool $absolute = false, ?int $expiresInMinutes = null): string
    {
        return URL::temporarySignedRoute(
            'api.v1.auth.verify-registration',
            now()->addMinutes($expiresInMinutes ?? (int) config('auth.verification.expire', 1440)),
            [
                'user' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ],
            $absolute,
        );
    }

    public function test_verifies_email_and_returns_token(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'verify@example.com',
        ]);

        $response = $this->getJson($this->signedUrl($user));

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Email успешно подтвержден')
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => ['id', 'email', 'email_verified'],
                ],
            ]);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertNotEmpty($response->json('data.token'));
        $this->assertTrue($response->json('data.user.email_verified'));
    }

    public function test_already_verified_user_still_receives_token(): void
    {
        $user = User::factory()->create([
            'email' => 'already@example.com',
        ]);

        $response = $this->getJson($this->signedUrl($user));

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Email адрес уже подтвержден')
            ->assertJsonPath('data.user.email_verified', true);

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_invalid_signature_returns_russian_message(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'bad-signature@example.com',
        ]);

        $hash = sha1($user->getEmailForVerification());
        $expires = now()->addHour()->timestamp;

        $response = $this->getJson(
            "/api/v1/verify-registration/{$user->id}/{$hash}?expires={$expires}&signature=invalid"
        );

        $response->assertForbidden()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Неверная или истекшая ссылка подтверждения. Запросите новое письмо.')
            ->assertJsonPath('code', 'invalid_signature');

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_expired_signature_returns_russian_message(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'expired@example.com',
        ]);

        $url = $this->signedUrl($user, false, 1);

        $this->travel(2)->hours();

        $response = $this->getJson($url);

        $response->assertForbidden()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Неверная или истекшая ссылка подтверждения. Запросите новое письмо.');
    }

    public function test_invalid_hash_is_rejected(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'wrong-hash@example.com',
        ]);

        $url = URL::temporarySignedRoute(
            'api.v1.auth.verify-registration',
            now()->addDay(),
            [
                'user' => $user->getKey(),
                'hash' => sha1('other@example.com'),
            ],
            false,
        );

        $this->getJson($url)
            ->assertForbidden()
            ->assertJsonPath('message', 'Неверная или истекшая ссылка подтверждения. Запросите новое письмо.');
    }
}
