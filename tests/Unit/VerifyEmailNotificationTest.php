<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

final class VerifyEmailNotificationTest extends TestCase
{
    public function test_frontend_link_includes_relative_signature_and_24h_expiry(): void
    {
        Config::set('app.frontend_url', 'https://vedminozelie.ru/');
        Config::set('app.frontend_verify_path', '/verify-registration');
        Config::set('auth.verification.expire', 60 * 24);

        $user = new User;
        $user->id = 15;
        $user->email = 'notify@example.com';

        $mail = (new VerifyEmailNotification)->toMail($user);
        $url = $mail->actionUrl;

        $this->assertNotNull($url);
        $this->assertStringStartsWith(
            'https://vedminozelie.ru/verify-registration/15/'.sha1('notify@example.com'),
            $url
        );
        $this->assertStringContainsString('expires=', $url);
        $this->assertStringContainsString('signature=', $url);
        $this->assertStringContainsString(
            '24 ч',
            implode(' ', array_merge($mail->introLines, $mail->outroLines))
        );

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $this->assertGreaterThan(now()->addHours(23)->timestamp, (int) ($query['expires'] ?? 0));
        $this->assertLessThanOrEqual(now()->addHours(25)->timestamp, (int) ($query['expires'] ?? 0));
    }
}
