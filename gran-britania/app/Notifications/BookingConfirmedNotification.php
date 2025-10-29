<?php

namespace App\Notifications;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class BookingConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public ClassBooking $booking) {}

    public function via($notifiable): array { return ['mail']; }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('¡Reserva confirmada!')
            ->greeting("Hola {$this->booking->name}")
            ->line('Tu clase ha sido CONFIRMADA.')
            ->line('Fecha: '.\Carbon\Carbon::parse($this->booking->class_date)->format('d/m/Y'))
            ->line('Hora: '.substr($this->booking->class_time,0,5));

        // Si existe URL de videollamada, la usamos
        $url = $this->booking->meeting_url ?? null;
        if (! empty($url)) {
            try {
                $signed = URL::temporarySignedRoute('bookings.join', now()->addDays(7), ['booking' => $this->booking->id]);
                $mail->action('Entrar a la videollamada', $signed)
                     ->line('También puedes acceder a la videollamada mediante el enlace que aparece arriba.');
            } catch (\Throwable $e) {
                // si falla la generación de URL firmada, incluimos la URL directa
                $mail->line('Enlace de la videollamada: '.$url);
            }

            // Añadimos siempre también la URL directa visible para Ethereal/Sistemas que no muestren botones
            $mail->line('Enlace directo: '.$url);
        }

        $mail->salutation('— Gran Bretania');

        return $mail;
    }
}
