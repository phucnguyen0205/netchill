<?php
// app/Notifications/CommentReplied.php
namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentReplied extends Notification
{
    use Queueable;

    public function __construct(public Comment $reply, public Comment $parent) {}

    public function via($notifiable)
    {
        return ['database']; // có thể thêm 'mail' nếu cần
    }

    public function toDatabase($notifiable)
    {
        $movie = $this->reply->movie; // quan hệ comment -> movie
        return [
            'title'     => 'Có người trả lời bình luận của bạn',
            'by'        => $this->reply->user?->name ?? 'Ai đó',
            'movie'     => $movie?->title,
            'movieSlug' => $movie?->slug,
            'reply_id'  => $this->reply->id,
            'parent_id' => $this->parent->id,
            // URL trỏ tới trang phim + anchor tới comment gốc
            'url'       => $movie ? route('movies.detai', $movie->slug) . '#cmt-' . $this->parent->id : url('/'),
        ];
    }
}
