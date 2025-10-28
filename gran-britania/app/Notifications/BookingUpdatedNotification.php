<?php

namespace App\Notifications;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(public ClassBooking $booking) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reserva modificada')
            ->greeting("Hola {$this->booking->name}")
            ->line('Tu reserva ha sido modificada con éxito.')
            ->line('Nueva fecha: '.\Carbon\Carbon::parse($this->booking->class_date)->format('d/m/Y'))
            ->line('Nueva hora: '.substr($this->booking->class_time,0,5))
            ->lineIf($this->booking->notes, "Notas: {$this->booking->notes}")
            ->salutation('— Gran Bretania');
    }
}
