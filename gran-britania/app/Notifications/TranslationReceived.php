<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TranslationRequest;

class TranslationReceived extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public TranslationRequest $tr)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($n): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $n): MailMessage
    {
        return (new MailMessage)
            ->subject('Hemos recibido tu solicitud de traducción')
            ->greeting('¡Gracias!')
            ->line("Idiomas: {$this->tr->source_lang} → {$this->tr->target_lang}")
            ->line("Urgencia: ".($this->tr->urgency ?? 'normal'))
            ->line('Te escribiremos con los siguientes pasos.');
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
