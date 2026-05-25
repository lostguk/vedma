<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * Нотификация для подтверждения email адреса
 */
final class VerifyEmailNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Сгенерировать подписанный API-URL и конвертировать его в фронтовую ссылку
        $signedApiUrl = $this->verificationUrl($notifiable);

        $frontendUrl = rtrim(Config::get('app.frontend_url'), '/');
        $frontendPath = '/'.ltrim(Config::get('app.frontend_verify_path'), '/');
        $frontendPath = rtrim($frontendPath, '/');
        $userId = $notifiable->getKey();
        $emailHash = sha1($notifiable->getEmailForVerification());

        // Переносим все query-параметры из подписанного URL на фронт
        $query = parse_url($signedApiUrl, PHP_URL_QUERY) ?: '';
        $verificationUrl = $frontendUrl.$frontendPath.'/'.$userId.'/'.$emailHash.($query ? ('?'.$query) : '');

        return (new MailMessage)
            ->subject('Подтверждение регистрации на сайте Ведьмино зелье')
            ->greeting('Здравствуйте!')
            ->line('Спасибо за регистрацию на сайте Ведьмино зелье.')
            ->line('Пожалуйста, подтвердите ваш email адрес, нажав на кнопку ниже:')
            ->action('Подтвердить Email', $verificationUrl)
            ->line('Эта ссылка действительна в течение 60 минут.')
            ->line('Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.');
    }

    /**
     * Генерация подписанного URL для верификации email
     */
    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'api.v1.auth.verify-registration',
            now()->addMinutes(60),
            [
                'user' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
