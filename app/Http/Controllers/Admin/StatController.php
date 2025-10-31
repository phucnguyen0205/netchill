<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\User;
use App\Models\ViewingProgress; // dùng làm lượt xem
use App\Models\Rating;          // dùng làm đánh giá
use Illuminate\Http\Request;
use App\Models\Payment;


class StatController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy dữ liệu KPI Người dùng
        $totalUsers = User::count();
        
        // Giả định: Người dùng Premium được đánh dấu bằng cột 'is_premium' = true (hoặc 1)
        $preUsers = User::where('is_premium', true)->count();
        
        // Số người dùng chưa Premium
        $nonPreUsers = $totalUsers - $preUsers;
        
        // 2. Lấy dữ liệu Thống kê Phim (Bạn đã làm tốt phần này)
        $query = Movie::query();
        if ($q = $request->get('q')) {
             $query->where('title', 'like', "%{$q}%");
        }
        
        // Lấy Top Views/Ratings/Interactions (chỉ là ví dụ, bạn có thể đã có logic khác)
        $topViews = (clone $query)->withCount('watchHistories')
                                 ->orderByDesc('watch_histories_count')
                                 ->limit(10)->get();
        
        $topRatings = (clone $query)->withCount('ratings')
                                  ->orderByDesc('ratings_count')
                                  ->limit(10)->get();

        $topInteractions = (clone $query)->withCount(['comments', 'ratings'])
                                        ->get()
                                        ->sortByDesc(fn ($m) => $m->comments_count + $m->ratings_count)
                                        ->take(10);


        // 3. Trả về View
        return view('admin.stats.index', [
            // KPI Người dùng
            'totalUsers' => $totalUsers,
            'preUsers' => $preUsers,
            'nonPreUsers' => $nonPreUsers,

            // Thống kê Phim
            'topViews' => $topViews,
            'topRatings' => $topRatings,
            'topInteractions' => $topInteractions,
            'totalUsers','preUsers','nonPreUsers',
            'topViews','topRatings','topInteractions'
        ]);
    }
}
