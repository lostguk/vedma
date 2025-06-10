<?php

declare(strict_types=1);

namespace App\Services\Mail;

use App\Mail\Auth\ResetPasswordMail;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

final readonly class MailService
{
    public function send(string $to, Mailable $mailable): void
    {
        Mail::to($to)->send($mailable);
    }

    /**
     * Отправить ссылку для сброса пароля
     */
    public function sendPasswordResetLink(string $email, string $url): void
    {
        Mail::to($email)->send(new ResetPasswordMail($url));
    }
}
