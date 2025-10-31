<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Favorite, Movie, Person}; // đổi Person nếu là Actor

class FavoriteController extends Controller
{
    public function index(Request $r)
    {
        $tab    = $r->get('tab', 'movies'); // movies|people
        $user   = $r->user();

        $movies = $user->favoriteMovies()->get();
        $people = $user->favoritePeople()->get();

        return view('profile.favourites.index', compact('tab','movies','people'));
    }

    public function __construct()
    {
        $this->middleware('auth'); // đảm bảo phải đăng nhập
    }
    // Add favorite
    public function store(Request $r)
    {
        $data = $r->validate([
            'type' => 'required|in:movie,person',
            'id'   => 'required|integer',
        ]);

        $user  = $r->user();
        $model = $data['type'] === 'movie'
            ? Movie::findOrFail($data['id'])
            : Person::findOrFail($data['id']);

        Favorite::firstOrCreate([
            'user_id'          => $user->id,
            'favoritable_id'   => $model->getKey(),
            'favoritable_type' => $model->getMorphClass(), // 'movie' / 'person' nếu có morphMap
        ]);

        return $r->expectsJson()
            ? response()->json(['ok' => true, 'state' => 'added'])
            : back()->with('success','Đã thêm vào yêu thích');
    }

    public function destroy(Request $r)
    {
        $data = $r->validate([
            'type' => 'required|in:movie,person',
            'id'   => 'required|integer',
        ]);

        $user = $r->user();

        Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $data['id'])
            ->where('favoritable_type', $data['type'] === 'movie'
                ? (new Movie)->getMorphClass()
                : (new Person)->getMorphClass()
            )
            ->delete();

        return $r->expectsJson()
            ? response()->json(['ok' => true, 'state' => 'removed'])
            : back()->with('success','Đã xóa khỏi yêu thích');
    }
    public function toggle(Request $r)
    {
        $data = $r->validate([
            'type' => 'required|in:movie,person', // cho cả person luôn
            'id'   => 'required|integer',
        ]);

        $user  = $r->user();
        $model = $data['type'] === 'movie'
            ? Movie::findOrFail($data['id'])
            : Person::findOrFail($data['id']);

        $favoritableType = $model->getMorphClass();

        $query = Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $model->getKey())
            ->where('favoritable_type', $favoritableType);

        if ($query->exists()) {
            $query->delete();
            return response()->json(['state' => 'removed']);
        }

        Favorite::firstOrCreate([
            'user_id'          => $user->id,
            'favoritable_id'   => $model->getKey(),
            'favoritable_type' => $favoritableType,
        ]);

        return response()->json(['state' => 'added']);
    }

}
