<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Auth\VerificationResendLimiter;
use Tests\TestCase;

final class VerificationResendLimiterTest extends TestCase
{
    public function test_allows_one_attempt_then_blocks_until_decay(): void
    {
        config(['auth.verification.resend_decay' => 60]);

        $limiter = new VerificationResendLimiter;
        $email = 'Limit.User@Example.com';

        $this->assertFalse($limiter->tooManyAttempts($email));

        $limiter->hit($email);

        $this->assertTrue($limiter->tooManyAttempts($email));
        $this->assertTrue($limiter->tooManyAttempts('limit.user@example.com'));
        $this->assertGreaterThan(0, $limiter->availableIn($email));
        $this->assertLessThanOrEqual(60, $limiter->availableIn($email));

        $this->travel(61)->seconds();

        $this->assertFalse($limiter->tooManyAttempts($email));
    }
}
