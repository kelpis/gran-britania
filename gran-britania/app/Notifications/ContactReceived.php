<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // si quieres seguir encolándola
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactMessage $msg) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            // Copiamos a la admin en BCC. Usa el correo que prefieras aquí:
            //->cc(env('ADMIN_EMAIL'))
            ->subject('Hemos recibido tu mensaje')
            ->greeting('¡Gracias por contactar con Gran Bretania!')
            ->line('Asunto: ' . ($this->msg->subject ?: 'Sin asunto'))
            ->line('Copia de tu mensaje:')
            ->line($this->msg->message)
            ->line('Te responderemos lo antes posible.');
    }
}
