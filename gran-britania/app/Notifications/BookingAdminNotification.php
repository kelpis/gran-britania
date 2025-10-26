<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ClassBooking;

class BookingAdminNotification extends Notification
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
            ->subject('Nueva reserva pendiente')
            ->greeting('¡Nueva solicitud de clase!')
            ->line("Fecha: {$this->booking->class_date}")
            ->line("Hora: {$this->booking->class_time}")
            ->line("Nombre: {$this->booking->name}")
            ->line("Email: {$this->booking->email}")
            ->lineIf($this->booking->phone, "Teléfono: {$this->booking->phone}")
            ->lineIf($this->booking->notes, "Notas: {$this->booking->notes}")
            ->salutation('— Sistema de reservas Gran Bretania');
    }
}
