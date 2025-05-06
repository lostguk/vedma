<?php

declare(strict_types=1);

namespace App\Services\Mail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailable;

final readonly class MailService
{
    public function send(string $to, Mailable $mailable): void
    {
        Mail::to($to)->send($mailable);
    }
}