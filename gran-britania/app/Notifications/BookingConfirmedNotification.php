<?php

namespace App\Notifications;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public ClassBooking $booking) {}

    public function via($notifiable): array { return ['mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('¡Reserva confirmada!')
            ->greeting("Hola {$this->booking->name}")
            ->line('Tu clase ha sido CONFIRMADA.')
            ->line('Fecha: '.\Carbon\Carbon::parse($this->booking->class_date)->format('d/m/Y'))
            ->line('Hora: '.substr($this->booking->class_time,0,5))
            // ->action('Entrar a la videollamada', $this->booking->meet_url ?? '#') // cuando lo tengas
            ->salutation('— Gran Bretania');
    }
}
