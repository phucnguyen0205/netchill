<?php
// app/Http/Controllers/WatchHistoryController.php
namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\ViewingProgress;

class WatchHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = $request->user()
            ->viewingProgresses()            // đổi quan hệ
            ->with('movie')
            ->orderByDesc('last_watched_at')
            ->paginate(20);

            return view('profile.history.index', compact('histories'));
    }

    public function update(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'position' => 'required|integer|min:0',
            'duration' => 'nullable|integer|min:0',
        ]);

        $duration = max((int)($data['duration'] ?? 0), 0);
        $position = max((int)$data['position'], 0);
        if ($duration > 0) $position = min($position, $duration);

        $progress = $duration > 0 ? (int) floor($position * 100 / $duration) : 0;

        $history = ViewingProgress::updateOrCreate(  // dùng model mới
            ['user_id' => $request->user()->id, 'movie_id' => $movie->id],
            [
                'last_position'   => $position,
                'duration'        => $duration,
                'progress'        => $progress,
                'last_watched_at' => now(),
                'completed_at'    => ($progress >= 95) ? now() : null,
            ]
        );

        return response()->json([
            'ok' => true,
            'progress' => $history->progress,
            'resume_url' => route('movies.show', $movie->slug) . '?t=' . $history->last_position,
        ]);
    }
}
