<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Comment;

class CommentFeedController extends Controller
{
    public function feed(Request $request)
    {
        // since: unix ms của lần cập nhật gần nhất trên client (optional)
        $sinceMs = $request->integer('since', 0);
        $since   = $sinceMs > 0 ? Carbon::createFromTimestampMs($sinceMs) : null;

        // Lấy comment mới hơn 'since' (nếu có)
        $baseSelect = ['id','user_id','movie_id','content','created_at'];

        $new = Comment::with([
                    'user:id,name', 
                    'movie:id,slug,title'
                ])
                ->select($baseSelect)
                ->when($since, fn($q) => $q->where('created_at','>', $since))
                ->latest('created_at')
                ->take(5)
                ->get();

        if ($new->isNotEmpty()) {
            return response()->json([
                'mode'     => 'new',
                'since'    => now()->getTimestampMs(),
                'comments' => $new->map(fn($c)=>$this->mapItem($c))
            ]);
        }

        // Không có comment mới: trả ngẫu nhiên 5 cái cho khỏi trống
        $random = Comment::with(['user:id,name', 'movie:id,slug,title'])
                    ->select($baseSelect)
                    ->inRandomOrder()
                    ->take(5)
                    ->get();

        return response()->json([
            'mode'     => 'random',
            'since'    => now()->getTimestampMs(),
            'comments' => $random->map(fn($c)=>$this->mapItem($c))
        ]);
    }

    private function mapItem(Comment $c): array
    {
        return [
            'id'         => $c->id,
            'user_name'  => $c->user->name ?? 'Người dùng',
            'avatar'     => 'https://i.pravatar.cc/150?u=' . ($c->user_id ?? $c->id),
            'movie_title'=> $c->movie->title ?? 'Không rõ',
            'movie_slug' => $c->movie->slug ?? '',
            'content'    => $c->content ?? '',
            'created_at' => $c->created_at?->toIso8601String(),
            'time_ago'   => optional($c->created_at)->diffForHumans(), // ví dụ "2 phút trước"
        ];
    }
}
