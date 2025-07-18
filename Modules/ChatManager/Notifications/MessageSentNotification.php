<?php

namespace Modules\ChatManager\Notifications;

use Illuminate\Bus\Queueable;
use Modules\ChatManager\Entities\Message;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageSentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new Message $message instance.
     */
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('You\'ve received a new message')
                    ->line('Hi '.ucfirst($this->message->receiver->full_name))
                    ->line('The following message was sent on your behalf to the '.ucfirst($this->message->sender->full_name).':')
                    ->line('Message: '.ucfirst($this->message->message))
                    ->action('Click here to see', route('conversations.index', $this->message->sender->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'data'=> [
                'avatar'=> \Storage::disk('public')->has($this->message->sender->avatar) ? asset('storage/'.$this->message->sender->avatar) : asset('images/no-img-100x92.jpg'),
                'name'=> $this->message->sender->full_name,
                'message'=> 'Sent you a new message ...',
                'link'=> route('conversations.index', $this->message->sender->id)
            ]
        ];
    }
}
