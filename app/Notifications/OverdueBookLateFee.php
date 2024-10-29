<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueBookLateFee extends Notification implements ShouldQueue
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
                    ->subject('Overdue Book Late Fee Payment')
                    ->greeting('Hello ' . $notifiable->name)
                    ->line('This is a reminder that your borrowed book "' . $this->borrow->book->title . '" is not returned on time.')
                    ->line('Please pay the late fee of Rs."' . $this->borrow->late_fee . '" to avoid any inconvenience.')
                    ->action('Pay here', route('payment.form', ['id' => $this->borrow->id]))
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
