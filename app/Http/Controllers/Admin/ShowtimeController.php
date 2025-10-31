<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Showtime, Movie};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;              
use Illuminate\Support\Facades\Schema;


class ShowtimeController extends Controller
{
    public function index(Request $r)
    {
        $q = Showtime::with('movie:id,title,slug')
            ->orderBy('show_date')->orderBy('start_time');

        if ($r->filled('from') && $r->filled('to')) {
            $q->whereBetween('show_date', [$r->date('from'), $r->date('to')]);
        } elseif ($r->filled('from')) {
            $q->where('show_date', '>=', $r->date('from'));
        } elseif ($r->filled('to')) {
            $q->where('show_date', '<=', $r->date('to'));
        }

        if ($r->filled('movie_id')) {
            $q->where('movie_id', $r->integer('movie_id'));
        }
        if ($r->filled('room')) {
            $q->where('room', 'like', '%'.$r->string('room').'%');
        }

        $showtimes = $q->paginate(12)->withQueryString();
        $movies    = Movie::orderBy('title')->pluck('title','id');

        return view('admin.showtimes.index', compact('showtimes','movies'));
    }

    public function create() {
        $movies = \App\Models\Movie::series()->orderBy('title')->pluck('title','id');
        return view('admin.showtimes.create', compact('movies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'movie_id'     => ['required','exists:movies,id'],
            'episode_number' => ['nullable','integer','min:1'],
            'show_date'    => ['required','date'],
            'start_time'   => ['required','date_format:H:i'],
            'end_time'     => ['nullable','date_format:H:i','after:start_time'],
            'room'         => ['nullable','string','max:50'],
            'is_premiere'  => ['nullable','boolean'],
            'note'         => ['nullable','string','max:1000'],
        ]);

        // Nếu là phim bộ và chưa truyền episode_number → tự điền tập kế tiếp
        $movie = Movie::findOrFail($data['movie_id']);
        if ($movie->is_series && empty($data['episode_number'])) {
            $max = Showtime::where('movie_id', $movie->id)->max('episode_number');
            $data['episode_number'] = (int)($max ?? 0) + 1;
        }

        $data['is_premiere'] = (bool)($request->boolean('is_premiere'));

        Showtime::create($data);

        return redirect()->route('admin.showtimes.index')->with('status','Đã tạo lịch chiếu.');
    }

    public function edit(\App\Models\Showtime $showtime) {
        $movies = \App\Models\Movie::series()->orderBy('title')->pluck('title','id');
        return view('admin.showtimes.edit', compact('showtime','movies'));
    }

    public function update(Request $r, Showtime $showtime)
{
    $data = $r->validate([
        'movie_id' => [
            'required',
            Rule::exists('movies','id')->where(fn($q) => $q->where('is_series',1)),
        ],
        'show_date'  => ['required','date'],
        'start_time' => ['required','date_format:H:i'],
        'end_time'   => ['nullable','date_format:H:i','after:start_time'],
        'room'       => ['nullable','max:50'],
        'is_premiere'=> ['boolean'],
        'note'       => ['nullable','max:255'],
    ]);

        // tránh trùng với suất khác
        $dup = Showtime::where('movie_id',$data['movie_id'])
            ->where('show_date',$data['show_date'])
            ->where('start_time',$data['start_time'])
            ->where('id','<>',$showtime->id)
            ->exists();
        if ($dup) {
            return back()->withInput()->withErrors([
                'start_time' => 'Suất này bị trùng với bản ghi khác.'
            ]);
        }

        $showtime->update($data);
        return redirect()->route('admin.showtimes.index')->with('ok','Đã cập nhật.');
    }
    
    public function nextEpisode(Movie $movie)
    {
        // Chỉ áp dụng phim bộ
        if (!$movie->is_series) {
            return response()->json(['next_episode' => null]);
        }

        // Lấy tập lớn nhất đã có trong showtimes
        $max = Showtime::where('movie_id', $movie->id)->max('episode_number');

        return response()->json(['next_episode' => (int)($max ?? 0) + 1]);
    }
    
    public function destroy(Showtime $showtime)
    {
        $showtime->delete();
        return back()->with('ok','Đã xóa.');
    }
}
