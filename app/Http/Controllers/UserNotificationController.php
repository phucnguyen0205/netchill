<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class UserNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(20);
        $unreadCount   = $request->user()->unreadNotifications()->count();

        return view('profile.notifications.index', compact('notifications','unreadCount'));
    }

    public function read(Request $request, DatabaseNotification $notification)
    {
        abort_unless($notification->notifiable_id === $request->user()->id, 403);
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }
        return response()->noContent();
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('status', 'Đã đánh dấu tất cả thông báo là đã đọc');
    }
}
