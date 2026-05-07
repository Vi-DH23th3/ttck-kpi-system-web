<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CongViecNotification extends Notification
{
    use Queueable;
    public $title;
    public $message; 
    public $link;
    //public $type;
    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $link ='#')
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            // 'title' => 'Thông báo từ hệ thống KPI',
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
        ];
    }
}
