<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Notifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($user , $message)
    {
        $this->user = $user ;
        if($this->user->role == 'admin'){
            $this->message_admin = $message ;
        }else{
            $this->message_employe = $message ;
        }
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
        if($user->role == 'admin'){
            return [
                $this->employe->full_name,
            ];
        }else{
            return [
                'full_name' => $this->employe->full_name,
                'message' => $this->$message
            ];
        }
    }
}
