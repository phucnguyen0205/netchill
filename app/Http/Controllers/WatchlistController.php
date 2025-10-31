<?php
// app/Http/Controllers/WatchlistController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;

class WatchlistController extends Controller
{
    // app/Http/Controllers/WatchlistController.php
public function index(Request $request)
{
    $watchlists = Watchlist::where('user_id', auth()->id())
                    // ->withCount('items') // nếu có
                    ->latest()->get();

    // ===== THÊM: nạp phim khi có ?open=ID =====
    $movies = null;
    if ($request->filled('open')) {
        $wl = Watchlist::where('user_id', auth()->id())
            ->where('id', $request->integer('open'))
            ->first();
        if ($wl && method_exists($wl, 'movies')) {
            $movies = $wl->movies()->latest()->get(); // hoặc ->paginate(12)
        }
    }

    return view('profile.watchlist.index', compact('watchlists','movies'));
}

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        Watchlist::create(['user_id' => auth()->id(), 'name' => $data['name']]);
        return redirect()->route('watchlists.index')->with('success', 'Thêm danh sách mới thành công!');
    }
    public function update(Request $request, Watchlist $watchlist)
    {
        abort_unless($watchlist->user_id === auth()->id(), 403);
    
        $data = $request->validate(['name' => 'required|string|max:255']);
        $watchlist->update($data);
    
        return back()->with('success', 'Đã cập nhật playlist.');
    }
    
    public function destroy(\App\Models\Watchlist $watchlist)
    {
        abort_unless($watchlist->user_id === auth()->id(), 403);
        $watchlist->delete();
        return back()->with('success','Đã xóa playlist.');
    }
    
    public function removeItem(Watchlist $watchlist, \App\Models\Movie $movie){
        abort_unless($watchlist->user_id === auth()->id(), 403);
        $watchlist->movies()->detach($movie->id);
        return response()->json(['ok'=>true]);
    }
    public function addItem(Request $request, \App\Models\Watchlist $watchlist)
{
    abort_unless($watchlist->user_id === auth()->id(), 403);

    $data = $request->validate([
        'movie_id' => ['required','integer','exists:movies,id'],
    ]);

    $watchlist->movies()->syncWithoutDetaching([$data['movie_id']]);

    return response()->json([
        'ok' => true,
        'message' => 'Đã thêm vào danh sách.',
        'watchlist_id' => $watchlist->id,
    ]);
}

public function quickCreateAndAttach(Request $request)
{
    $data = $request->validate([
        'name'     => ['required','string','max:255'],
        'movie_id' => ['required','integer','exists:movies,id'],
    ]);

    $watchlist = \App\Models\Watchlist::create([
        'user_id' => auth()->id(),
        'name'    => $data['name'],
    ]);

    $watchlist->movies()->syncWithoutDetaching([$data['movie_id']]);

    return response()->json([
        'ok' => true,
        'message' => 'Đã tạo danh sách và thêm phim.',
        'watchlist' => [
            'id'   => $watchlist->id,
            'name' => $watchlist->name,
        ],
    ]);
}

public function movies()
{
    // nếu pivot là movie_watchlist (watchlist_id, movie_id, timestamps)
    return $this->belongsToMany(Movie::class, 'movie_watchlist', 'watchlist_id', 'movie_id')
                ->withTimestamps();
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
