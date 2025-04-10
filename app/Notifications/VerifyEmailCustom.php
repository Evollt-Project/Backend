<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailCustom extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

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
    public function toMail(object $user): MailMessage
    {
        $verificationUrl = $this->verificationUrl($user);

        return (new MailMessage)
            ->subject('Подтвердить адрес электронной почты')
            ->view('emails.verify-email', [
                'url' => $verificationUrl,
                'user' => $user,
            ]);
    }

    protected function verificationUrl($notifiable)
    {
        $params = [
            'id' => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification()),
        ];

        $signedUrl = URL::signedRoute(
            'verification.verify',
            $params,
            now()->addMinutes(60)
        );

        $parsed = parse_url($signedUrl);
        parse_str($parsed['query'], $queryParams);

        $frontendUrl = config('app.frontend_url') . '/auth/approve/email?' . http_build_query(
                array_merge($params, $queryParams)
            );

        return $frontendUrl;
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
