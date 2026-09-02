<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

final class VerificationResendLimiter
{
    public function tooManyAttempts(string $email): bool
    {
        return RateLimiter::tooManyAttempts($this->key($email), 1);
    }

    public function availableIn(string $email): int
    {
        return max(1, RateLimiter::availableIn($this->key($email)));
    }

    public function hit(string $email): void
    {
        RateLimiter::hit($this->key($email), $this->decaySeconds());
    }

    public function decaySeconds(): int
    {
        return max(1, (int) config('auth.verification.resend_decay', 60));
    }

    public function key(string $email): string
    {
        return 'verification-resend:'.Str::lower(trim($email));
    }
}
