<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function suggest(Request $request)
{
    $q = trim((string) $request->query('q', ''));
    if ($q === '') return response()->json(['data' => []]);

    $like = '%'.str_replace(' ', '%', $q).'%';

    // Chỉ dùng những cột có thật trong bảng
    $searchable = collect(['title','english_title','original_title'])
        ->filter(fn($c) => Schema::hasColumn('movies', $c))
        ->values()
        ->all();

    // Các cột để select cũng kiểm tra tồn tại
    $selects = array_filter([
        'id','slug','poster','age_rating','release_year','views',
        Schema::hasColumn('movies','title') ? 'title' : null,
        Schema::hasColumn('movies','english_title') ? 'english_title' : null,
        Schema::hasColumn('movies','original_title') ? 'original_title' : null,
    ]);

    $movies = Movie::query()
        ->select($selects)
        ->when(count($searchable), function ($qr) use ($like, $searchable) {
            $qr->where(function ($w) use ($like, $searchable) {
                foreach ($searchable as $col) {
                    $w->orWhere($col, 'like', $like);
                }
            });
        })
        ->orderByDesc('views')
        ->limit(8)
        ->get()
        ->map(function ($m) {
            return [
                'title'  => $m->title,
                'sub'    => $m->english_title ?? ($m->original_title ?? null),
                'slug'   => $m->slug,
                'year'   => $m->release_year ?? null,
                'age'    => $m->age_rating ?? null,
                'poster' => $m->poster ? \Storage::url($m->poster) : asset('images/placeholder-poster.jpg'),
                'url'    => route('movies.show', $m->slug),
            ];
        });

    return response()->json(['data' => $movies]);
}
}