<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;  // Cambia a public
    public $userName;  // Cambia a public

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $userName)
    {
        $this->title = $title;
        $this->userName = $userName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nuevo Post Publicado')
            ->greeting('Hola, Administrador')
            ->line("El usuario {$this->userName} ha publicado un nuevo post titulado: {$this->title}.")
            ->action('Ver Post', url("/posts/{$this->title}"))
            ->line('¡Revisa el sistema para más detalles!');
    }
}
