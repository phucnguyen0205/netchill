<?php
// app/Http/Controllers/EpisodeController.php
namespace App\Http\Controllers;

use App\Models\Episode;

class EpisodeController extends Controller
{
    public function show(Episode $episode)
    {
        // Eager-load để view dùng
        $episode->load([
            'season:id,movie_id,season_number,total_episodes',
            'season.movie:id,slug,title,poster,is_series',
        ]);

        $movie   = $episode->season->movie;     // để hiển thị thông tin phim
        $seasons = $movie->seasons()
                        ->with(['episodes' => fn($q)=>$q->orderBy('episode_number')])
                        ->orderBy('season_number')
                        ->get();

        // (tuỳ chọn) xác định tập kế/ trước để làm nút next/prev
        $next = Episode::whereHas('season', fn($q)=>$q->where('movie_id', $movie->id))
                ->where(function($q) use ($episode){
                    $q->where('episodes.episode_number','>', $episode->episode_number)
                      ->orWhere(function($q2) use ($episode){
                          $q2->where('episodes.episode_number', $episode->episode_number)
                             ->where('episodes.id','>', $episode->id);
                      });
                })
                ->orderBy('episode_number')
                ->first();

        $prev = Episode::whereHas('season', fn($q)=>$q->where('movie_id', $movie->id))
                ->where(function($q) use ($episode){
                    $q->where('episodes.episode_number','<', $episode->episode_number)
                      ->orWhere(function($q2) use ($episode){
                          $q2->where('episodes.episode_number', $episode->episode_number)
                             ->where('episodes.id','<', $episode->id);
                      });
                })
                ->orderByDesc('episode_number')
                ->first();

        return view('episodes.show', compact('episode','movie','seasons','next','prev'));
    }
}
