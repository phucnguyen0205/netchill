<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    /**
     * Hiển thị trang quản lý tài khoản chính.
     */
    public function index()
    {
        // Giả định view này là trang hiển thị form cập nhật tài khoản
        return view('profile.index');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Lưu vào DB, ví dụ:
        auth()->user()->watchlists()->create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Thêm danh sách mới thành công!');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required','string','max:255'],
            'gender' => ['nullable', Rule::in(['male','female','unknown'])],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp,avif,gif','max:2048'],
        ]);

        $user = $request->user();

        // Cập nhật avatar nếu có file
        if ($request->hasFile('avatar')) {
            $old = $user->avatar;
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;

            // xóa file cũ (nếu tồn tại)
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
        }

        // Cập nhật thông tin khác
        $user->name   = $data['name'];
        if (array_key_exists('gender', $data)) {
            $user->gender = $data['gender'];
        }
        $user->save();

        return back()->with('success', 'Đã cập nhật tài khoản.');
    }
    // --- CÁC PHƯƠNG THỨC MỚI ĐƯỢC YÊU CẦU ---

    /**
     * Hiển thị danh sách Yêu thích (Favourites) của người dùng.
     */
    public function favourites()
    {
        // Logic để lấy dữ liệu phim yêu thích (ví dụ: Auth::user()->favourites)
        // ...

        return view('profile.favourites'); // Giả định view là resources/views/profile/favourites.blade.php
    }

    /**
     * Hiển thị danh sách muốn xem (Watchlist) của người dùng.
     */
    public function watchlist()
    {
        // Logic để lấy dữ liệu phim trong danh sách muốn xem
        // ...

        return view('profile.watchlist'); // Giả định view là resources/views/profile/watchlist.blade.php
    }

    /**
     * Hiển thị danh sách đã xem gần đây (History / Xem tiếp) của người dùng.
     */
    public function history()
    {
        // Logic để lấy lịch sử xem của người dùng
        // ...

        return view('profile.history'); // Giả định view là resources/views/profile/history.blade.php
    }

    /**
     * Hiển thị trang Thông báo (Notifications) của người dùng.
     */
    public function notifications()
{
    $user = auth()->user();

    // Lấy nhanh số chưa đọc + vài bản ghi cho dropdown
    $unreadCount = $user->unreadNotifications()->count();

    // Giả sử bạn lưu 'category' trong notification->data (movie|community) và 'url' để đi tới comment
    $latest = $user->notifications()->latest()->limit(10)->get();

    // Group cho tabs (nếu chưa có 'category', bạn có thể gán mặc định 'movie')
    $notifMovies    = $latest->where('data.category', 'movie');
    $notifCommunity = $latest->where('data.category', 'community');
    $notifRead      = $latest->whereNotNull('read_at');

    return view('profile.notification.index', compact(
        'unreadCount', 'latest', 'notifMovies', 'notifCommunity', 'notifRead'
    ));
}

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required','current_password'],
        'password' => [
            'required','confirmed',
            \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers(),
        ],
    ]);

    $user = $request->user();
    $user->password = bcrypt($request->password);
    $user->save();

    return back()->with('pw_changed', true)
                 ->with('success', 'Đổi mật khẩu thành công.');
}

}
