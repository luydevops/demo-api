<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewPostNotification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        // Retrieve all posts with their associated user
        return response()->json(Post::with('user')->get(), 200);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        // Validar la solicitud entrante
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // Crear un nuevo post
        $post = Post::create($validated);

        // Obtener el usuario que creó el post
        $user = $post->user; // Asegúrate de que el modelo Post tenga la relación 'user' definida

        // Obtener los administradores
        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        // Enviar la notificación a los administradores
        Notification::send($admins, new NewPostNotification($post, $user));

        return response()->json(['message' => 'Post creado exitosamente', 'data' => $post], 201);
    }

    /**
     * Display the specified post.
     */
    public function show($id)
    {
        // Find post by ID
        $post = Post::with('user')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post, 200);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Find the post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Update post details
        $post->update([
            'title' => $validated['title'] ?? $post->title,
            'content' => $validated['content'] ?? $post->content,
            'user_id' => $validated['user_id'] ?? $post->user_id,
        ]);

        return response()->json(['message' => 'Post updated successfully', 'data' => $post], 200);
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy($id)
    {
        // Find the post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
