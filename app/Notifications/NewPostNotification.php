<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $userName;

    public function __construct($title, $userName)
    {
        $this->title = $title;
        $this->userName = $userName;
    }

    public function via($notifiable)
    {
        return ['mail']; // Especifica que se debe usar el canal de correo
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nuevo Post Publicado')
            ->greeting('Hola Administrador,')
            ->line("El usuario {$this->userName} ha publicado un nuevo post titulado: {$this->title}.")
            ->line('¡Gracias por estar al pendiente!');
    }
}
