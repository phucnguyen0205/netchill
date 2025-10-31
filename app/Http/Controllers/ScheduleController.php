<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $r)
    {
        // Cửa sổ 7 ngày: hôm nay -> +6 ngày (tự động đổi theo thời gian thực)
        $windowStart = Carbon::today();               // luôn là "hôm nay"
        $windowEnd   = (clone $windowStart)->addDays(6);

        // Ngày đang xem: mặc định hôm nay, nếu user chọn ngày ngoài cửa sổ thì "kẹp" vào trong
        $active = Carbon::parse($r->input('date', $windowStart->toDateString()));
        if ($active->lt($windowStart)) $active = (clone $windowStart);
        if ($active->gt($windowEnd))   $active = (clone $windowEnd);

        // Danh sách 7 ngày để render thanh ngày
        $weekDates = collect(range(0, 6))->map(fn($i) => (clone $windowStart)->addDays($i));

        // Lấy lịch trong 7 ngày
        $byDate = \App\Models\Showtime::with(['movie:id,slug,title,poster'])
        ->whereBetween('show_date', [$windowStart->toDateString(), $windowEnd->toDateString()])
        ->whereHas('movie', fn($m) => $m->series())  
        ->orderBy('show_date')->orderBy('start_time')
        ->get()
        ->groupBy(fn($st) => $st->show_date->toDateString());
        $items = $byDate->get($active->toDateString(), collect());

        return view('schedule.index', [
            'weekDates'   => $weekDates,
            'activeDate'  => $active,
            'windowStart' => $windowStart,
            'windowEnd'   => $windowEnd,
            'items'       => $items,
        ]);
    }
}
