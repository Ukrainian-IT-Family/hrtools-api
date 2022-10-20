<?php

namespace App\Notifications;

use App\Helpers\HttpUrlHelper;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class EmailVerificationNotification extends VerifyEmail
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        $parseUrl = parse_url($verifyUrl);
        parse_str($parseUrl['query'], $parameters);
        $customUrl = HttpUrlHelper::removeDuplicates(
            env('APP_URL').'/auth/verify-email/'.$notifiable->getId()
            .'?expires='.$parameters['expires'].'&hash='.$parameters['hash'].'&signature='.$parameters['signature']
        );

        return (new MailMessage)
            ->line(__('register.please_click_the_button_below_to_verify_your_email_address'))
            ->action(__('register.verify_email_address'), $customUrl)
            ->line(__('register.thank_you_for_using_our_application'));
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
