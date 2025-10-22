<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TranslationRequest;

class TranslationAdminAlert extends Notification
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
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
             ->subject('Nueva solicitud de traducción')
            ->line("Nombre: {$this->tr->name}")
            ->line("Email: {$this->tr->email}")
            ->line("Idiomas: {$this->tr->source_lang} → {$this->tr->target_lang}")
            ->line("Urgencia: ".($this->tr->urgency ?? 'normal'))
            ->line("Comentarios: ".($this->tr->comments ?: '—'));
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
