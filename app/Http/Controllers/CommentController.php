<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\CommentReplied;

class CommentController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'is_secret' => 'nullable|boolean',
        ]);

        $comment = $movie->comments()->create([
            'user_id'   => $request->user()->id,
            'content'   => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
            'is_secret' => (bool)($data['is_secret'] ?? false),
        ])->load('user','replies.user');

        // notify người được trả lời (nếu có)
        if (!empty($data['parent_id'])) {
            $parent = Comment::with('user')->find($data['parent_id']);
            if ($parent && $parent->user_id !== $request->user()->id) {
                $parent->user->notify(new \App\Notifications\CommentReplied($comment, $parent));
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('movies.partials._comment_item', [
                'comment' => $comment,
                'isReply' => !empty($data['parent_id']),
            ])->render();

            return response()->json(['ok' => true, 'html' => $html, 'id' => $comment->id]);
        }
        if (!empty($data['parent_id'])) {
            $parent = Comment::with('user','movie')->find($data['parent_id']);
            if ($parent && $parent->user_id !== $request->user()->id) {
                $parent->user->notify(new CommentReplied($comment, $parent));
            }
        }

        return back();
    }
    // App/Http/Controllers/CommentController.php
public function update(Request $request, \App\Models\Comment $comment)
{
    abort_unless(auth()->id() === $comment->user_id, 403);
    $data = $request->validate(['content' => ['required','string','max:1000']]);
    $comment->update($data);

    if ($request->wantsJson()) {
        return response()->json(['ok' => true, 'content' => $comment->content]);
    }
    return back();
}

    
    public function destroy(Request $request, \App\Models\Comment $comment)
    {
        // Nếu dùng policy: $this->authorize('delete', $comment);
        abort_if($request->user()->id !== $comment->user_id, 403);
    
        $comment->delete();
        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('status', 'Đã xóa bình luận.');
    }
    public function vote(Request $r){
        $data = $r->validate([
            'comment_id' => ['required','exists:comments,id'],
            'vote'       => ['required','in:like,dislike'],
        ]);
        $comment = \App\Models\Comment::findOrFail($data['comment_id']);
        $value   = $data['vote'] === 'like' ? 1 : -1;
    
        $cv = \App\Models\CommentVote::updateOrCreate(
            ['user_id'=>$r->user()->id, 'comment_id'=>$comment->id],
            ['value'=>$value]
        );
    
        $likes    = $comment->votes()->where('value',1)->count();
        $dislikes = $comment->votes()->where('value',-1)->count();
    
        return response()->json(['likes'=>$likes,'dislikes'=>$dislikes,'my_vote'=>$cv->value]);
    }
    public function react(Request $request, Comment $comment)
    {
        $data = $request->validate(['value' => 'required|in:1,-1']);

        $reaction = $comment->reactions()->firstOrNew(['user_id' => $request->user()->id]);

        if (!$reaction->exists) {
            $reaction->value = (int)$data['value'];
            $reaction->save();
        } else {
            $same = ((int)$reaction->value === (int)$data['value']);
            $same ? $reaction->delete() : $reaction->update(['value' => (int)$data['value']]);
        }

        $comment->loadCount([
            'likesCount as likes_count',
            'dislikesCount as dislikes_count',
        ]);

        $mine = optional($comment->reactions()->where('user_id', $request->user()->id)->first())->value;

        return response()->json([
            'ok'            => true,
            'likes'         => $comment->likes_count,
            'dislikes'      => $comment->dislikes_count,
            'user_reaction' => $mine, // 1 | -1 | null
        ]);
    }
}
