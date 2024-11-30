<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Post;

class ReportController extends Controller
{
    /**
     * Generate a nested report of posts by user and date.
     */
    public function getPostsReport(Request $request)
    {
        // Validate input filters
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        // Fetch query parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query posts with details
        $posts = Post::select(
            DB::raw('DATE(created_at) as post_date'),
            'user_id',
            'title',
            'content',
            'created_at'
        )
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->with('user:id,name') // Include user information
            ->get();

        // Group posts by user and date
        $grouped = $posts->groupBy(['post_date', 'user_id']);

        // Transform data into nested structure
        $result = $grouped->map(function ($userPosts, $date) {
            return $userPosts->groupBy('user_id')->map(function ($posts, $userId) use ($date) {
                $user = $posts->first()->user;

                return [
                    'date' => $date,
                    'user_id' => $userId,
                    'user_name' => $user->name ?? 'Unknown',
                    'total_posts' => $posts->count(),
                    'posts' => $posts->map(function ($post) {
                        return [
                            'title' => $post->title,
                            'content' => $post->content,
                            'date' => $post->created_at->toDateTimeString(),
                        ];
                    })->values(),
                ];
            })->values();
        })->flatten(1);

        return response()->json($result, 200);
    }

    public function fetchExternalPosts()
    {
        // Consumir datos de la API JSONPlaceholder
        $response = Http::get('https://jsonplaceholder.typicode.com/posts');

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            return response()->json([
                'message' => 'Datos obtenidos con éxito',
                'data' => $response->json(),
            ], 200);
        }

        return response()->json(['message' => 'Error al obtener datos'], 500);
    }
}
