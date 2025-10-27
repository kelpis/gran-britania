<?php

namespace App\Notifications;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(public ClassBooking $booking) {}

    public function via($notifiable): array { return ['mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reserva cancelada')
            ->greeting("Hola {$this->booking->name}")
            ->line('Tu reserva ha sido cancelada.')
            ->line('Si necesitas, puedes volver a solicitar otra fecha y hora.')
            ->salutation('— Gran Bretania');
    }
}
