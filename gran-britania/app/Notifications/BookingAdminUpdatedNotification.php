<?php

namespace App\Notifications;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingAdminUpdatedNotification extends Notification
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
            ->subject('Reserva modificada por usuario')
            ->greeting('Reserva modificada')
            ->line("ID: {$this->booking->id}")
            ->line("Nombre: {$this->booking->name}")
            ->line("Email: {$this->booking->email}")
            ->line('Fecha: '.\Carbon\Carbon::parse($this->booking->class_date)->format('d/m/Y'))
            ->line('Hora: '.substr($this->booking->class_time,0,5))
            ->lineIf($this->booking->phone, "Teléfono: {$this->booking->phone}")
            ->lineIf($this->booking->notes, "Notas: {$this->booking->notes}")
            ->salutation('— Sistema de reservas Gran Bretania');
    }
}
