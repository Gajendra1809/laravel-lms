<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DueDateReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $borrow;

    public function __construct($borrow)
    {
        $this->borrow = $borrow;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Book Due Date Reminder')
                    ->greeting('Hello ' . $notifiable->name)
                    ->line('This is a reminder that your borrowed book "' . $this->borrow->book->title . '" is due tomorrow.')
                    ->line('Please return the book on time to avoid any late fees.')
                    ->action('View Borrowed Books', url(''))
                    ->line('Thank you for using our library!');
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
