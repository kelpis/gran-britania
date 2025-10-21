<?php

namespace App\Notifications;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactAdminAlert extends Notification implements ShouldQueue

{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ContactMessage $msg){}

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
            ->subject('Nuevo mensaje de contacto')
            ->greeting('Nuevo mensaje recibido desde la web')
            ->line('Nombre: ' . $this->msg->name)
            ->line('Email: ' . $this->msg->email)
            ->line('Asunto: ' . ($this->msg->subject ?: 'Sin asunto'))
            ->line('Mensaje:')
            ->line($this->msg->message);
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
