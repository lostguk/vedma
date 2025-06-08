<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Services\Mail\MailService;

class MailController extends Controller
{
    public function testMail(MailService $mailService): void
    {
        \Log::info('Отправка тестового письма');

        try {
            $mailService->send('vitalii.it.88@gmail.com', new TestMail);

            $this->success(['message' => 'Письмо отправлено']);

        } catch (\Throwable $e) {
            \Log::error('Ошибка отправки письма: '.$e->getMessage());
        }
    }
}
