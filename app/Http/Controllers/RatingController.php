<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // Đảm bảo routes dùng middleware('auth') rồi,
    // nhưng vẫn check phòng trường hợp gọi thẳng.
    // app/Http/Controllers/RatingController.php
public function store(Request $request, Movie $movie)
{
    if (!$request->user()) {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated.'], 401)
            : redirect()->route('login');
    }

    $data = $request->validate([
        'stars'   => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $rating = Rating::updateOrCreate(
        ['user_id' => $request->user()->id, 'movie_id' => $movie->id],
        ['stars' => $data['stars'], 'comment' => $data['comment'] ?? null]
    );

    $avg10 = round(($movie->ratings()->avg('stars') ?? 0) * 2, 1);
    $count = $movie->ratings()->count();

    return response()->json([
        'ok'         => true,
        'avg10'      => $avg10,
        'count'      => $count,
        'user_stars' => (int)$rating->stars,
    ]);
}

}
