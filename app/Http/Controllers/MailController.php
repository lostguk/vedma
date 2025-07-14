<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Services\Mail\MailService;

class MailController extends Controller
{
    /**
     * Отправка тестового письма на email администратора.
     *
     * @group Системные
     *
     * Этот endpoint отправляет тестовое письмо на указанный email для проверки работоспособности почтовой системы.
     *
     * @response 200 {"message": "Письмо отправлено"}
     */
    public function sendTestMail(MailService $mailService): void
    {
        \Log::info('Отправка тестового письма');

        try {
            $mailService->send('admin@row-dev.com', new TestMail);

            $this->success(['message' => 'Письмо отправлено']);

        } catch (\Throwable $e) {
            \Log::error('Ошибка отправки письма: '.$e->getMessage());
        }
    }
}
