<?php

namespace App\Notifications;

use App\Helpers\HttpUrlHelper;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailResetPasswordNotification extends ResetPassword
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = HttpUrlHelper::removeDuplicates(
            env('APP_URL').'/auth/reset-password/'.$this->token.'/'.$notifiable->getEmailForPasswordReset()
        );

        return (new MailMessage)
                ->subject(__('passwords.notification_subject'))
                ->line(__('passwords.notification_text'))
                ->action(__('passwords.notification_action'), $url)
                ->line(__('passwords.expired_in', ['minutes' => config('auth.passwords.users.expire')]))
                ->line(__('if_you_did_not_request_password_reset'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
