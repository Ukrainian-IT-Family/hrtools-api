<?php

namespace App\Notifications;

use App\Helpers\HttpUrlHelper;
use App\Models\Poll;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PollCompleteNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Poll $poll)
    {
        $this->poll = $poll;
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
        return (new MailMessage)
                    ->line(__('notifications.poll_auto_complete_title', ['title' => $this->poll->getTitle()]))
                    ->action(__('notifications.follow'), HttpUrlHelper::removeDuplicates(env('APP_URL').'/poll/'))
                    ->line(__('notifications.thank_you_for_using_our_application'));
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
