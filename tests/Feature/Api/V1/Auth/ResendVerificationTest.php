<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use RuntimeException;
use Tests\TestCase;

final class ResendVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resend_verification_sends_email_for_unverified_user(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create([
            'email' => 'unverified@example.com',
        ]);

        $response = $this->postJson(route('api.v1.auth.verify-registration.resend'), [
            'email' => 'unverified@example.com',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Письмо для подтверждения отправлено повторно.');

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    public function test_resend_verification_returns_ok_when_email_already_verified(): void
    {
        Notification::fake();

        User::factory()->create([
            'email' => 'verified@example.com',
        ]);

        $response = $this->postJson(route('api.v1.auth.verify-registration.resend'), [
            'email' => 'verified@example.com',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Email адрес уже подтвержден');

        Notification::assertNothingSent();
    }

    public function test_resend_verification_returns_not_found_for_unknown_email(): void
    {
        $response = $this->postJson(route('api.v1.auth.verify-registration.resend'), [
            'email' => 'missing@example.com',
        ]);

        $response->assertNotFound()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Пользователь не найден');
    }

    public function test_resend_verification_returns_error_when_sending_fails(): void
    {
        $this->mock(ChannelManager::class, function ($mock): void {
            $mock->shouldReceive('send')
                ->andThrow(new RuntimeException('Mail failed'));
        });

        User::factory()->unverified()->create([
            'email' => 'fail@example.com',
        ]);

        $response = $this->postJson(route('api.v1.auth.verify-registration.resend'), [
            'email' => 'fail@example.com',
        ]);

        $response->assertUnprocessable()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath(
                'message',
                'Не удалось отправить письмо для подтверждения. Проверьте адрес и попробуйте ещё раз.'
            )
            ->assertJsonPath('errors.email.0', 'Не удалось доставить письмо подтверждения.');
    }
}
