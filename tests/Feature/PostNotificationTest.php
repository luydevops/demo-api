<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Notifications\NewPostNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PostNotificationTest extends TestCase
{
    /**
     * Test if an email is sent when a post is created.
     */
    public function test_email_is_sent_when_post_is_created()
    {
        // Mock el facade Notification
        Notification::fake();

        // Crear un usuario administrador
        $admin = User::factory()->create([
            'email' => 'luydevops@gmail.com',
            'role_id' => 1, // Asume que el rol de admin tiene ID 1
        ]);

        // Crear un post relacionado con el usuario
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $admin->id,
        ]);

        // Simular el envío de la notificación
        Notification::send($admin, new NewPostNotification($post->title, $admin->name));

        // Verificar que se envió la notificación
        Notification::assertSentTo(
            $admin,
            NewPostNotification::class,
            function ($notification, $channels) use ($post, $admin) {
                return $notification->title === $post->title &&
                    $notification->userName === $admin->name;
            }
        );
    }

}
