<?php

declare(strict_types=1);

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/**
 * Письмо со ссылкой для восстановления пароля
 */
final class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $url,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Восстановление пароля')
            ->markdown('emails.auth.reset-password')
            ->with(['url' => $this->url]);
    }
}
