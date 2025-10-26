<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ClassBooking;

class BookingReceived extends Notification
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
            ->subject('Hemos recibido tu solicitud de clase')
            ->greeting("Hola {$this->booking->name} 👋")
            ->line("Hemos recibido tu solicitud para el día ".
                   \Carbon\Carbon::parse($this->booking->class_date)->format('d/m/Y').
                   " a las ".substr($this->booking->class_time,0,5).".")
            ->line('Tu reserva está en estado **pendiente**.')
            ->line('En breve la administradora te confirmará la cita.')
            ->salutation('— El equipo de Gran Bretania');
    }
}