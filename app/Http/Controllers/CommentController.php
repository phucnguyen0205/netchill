<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a new comment for a movie
     */
    public function store(Request $request, Movie $movie)
    {
        // Chỉ user đã đăng nhập mới comment
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id'  => auth()->id(),
            'movie_id' => $movie->id,
            'content'  => $request->input('content'),
        ]);

        return back()->with('success', 'Comment đã được đăng.');
    }
}
