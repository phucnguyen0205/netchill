<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use App\Models\Country;
use App\Models\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()->orderBy('name')->get(['id','slug','name']);
        $countries  = Country::query()->orderBy('name')->get(['id','slug','name']);
        $years      = range((int)date('Y'), 2010, -1);

        $categoryParam = $request->string('category')->toString(); // slug
        $countryParam  = $request->string('country')->toString();  // slug
        $typeParam     = $request->string('type')->toString();     // movie | series
        $ratingParam   = $request->string('rating')->toString();   // P|K|T13|...
        $versionParam  = $request->string('version')->toString();  // sub|dub|...
        $yearParam     = $request->integer('year');
        $sortParam     = $request->string('sort')->toString();     // latest|updated|imdb|views

        // ⬇️ dùng many-to-many: countries (số nhiều)
        $query = Movie::query()->with([
            'categories:id,name,slug',
            'countries:id,name,slug',
        ]);
        $query->when($request->filled('q'), function ($qr) use ($request) {
            $kw   = $request->string('q')->trim()->toString();
            $like = '%'.str_replace(' ', '%', $kw).'%';
        
            $searchable = collect(['title','english_title','original_title'])
                ->filter(fn($c) => Schema::hasColumn('movies', $c))
                ->values()
                ->all();
        
            if (!empty($searchable)) {
                $qr->where(function ($w) use ($like, $searchable) {
                    foreach ($searchable as $col) {
                        $w->orWhere($col, 'like', $like);
                    }
                });
            }
        });
        if ($categoryParam) {
            $query->whereHas('categories', function ($q) use ($categoryParam) {
                $q->where('categories.slug', $categoryParam);
            });
        }

        if ($countryParam) {
            $query->whereHas('countries', function ($q) use ($countryParam) {
                $q->where('countries.slug', $countryParam);
            });
        }

        if ($typeParam === 'movie')  { $query->where('is_series', false); }
        if ($typeParam === 'series') { $query->where('is_series', true); }
        if ($ratingParam)            { $query->where('age_rating', $ratingParam); }
        if ($versionParam)           { $query->where('version', $versionParam); }
        if ($yearParam)              { $query->where('release_year', $yearParam); }
        
        $query->when($sortParam === 'updated', fn($q) => $q->latest('updated_at'))
              ->when($sortParam === 'imdb',    fn($q) => $q->orderByDesc('imdb_score'))
              ->when($sortParam === 'views',   fn($q) => $q->orderByDesc('views'))
              ->when(!$sortParam || $sortParam === 'latest', fn($q) => $q->latest());

        $movies = $query->paginate(24)->withQueryString();
        $select = ['id','user_id','movie_id','content','created_at'];

        // Nếu có cột thì select, nếu không thì alias = 0 để tránh lỗi field list
        if (Schema::hasColumn('comments','likes')) {
            $select[] = 'likes';
        } else {
            $select[] = DB::raw('0 as likes');
        }
        if (Schema::hasColumn('comments','dislikes')) {
            $select[] = 'dislikes';
        } else {
            $select[] = DB::raw('0 as dislikes');
        }
        
        $orderExprParts = [];
        if (Schema::hasColumn('comments','likes'))    $orderExprParts[] = 'COALESCE(likes,0)';
        if (Schema::hasColumn('comments','dislikes')) $orderExprParts[] = 'COALESCE(dislikes,0)';
        
        $topComments = Comment::with(['user:id,name,email,avatar', 'movie:id,title,slug'])
            ->select($select)
            ->when($orderExprParts, function ($q) use ($orderExprParts) {
                $q->orderByRaw('(' . implode(' + ', $orderExprParts) . ') DESC');
            }, function ($q) {
                $q->latest(); // nếu không có likes/dislikes thì sắp xếp theo thời gian
            })
            ->take(5)
            ->get();
        $currentCategory = $categoryParam ? $categories->firstWhere('slug', $categoryParam) : null;

        return view('catalog.index', compact(
            'movies','categories','countries','years',
            'currentCategory','categoryParam','countryParam','typeParam',
            'ratingParam','versionParam','yearParam','sortParam'
        ));
    }

    public function byCategory(Category $category, Request $request)
    {
        // reuse index() bằng cách merge query param
        $request->merge(['category' => $category->slug]);
        return $this->index($request);
    }

    public function byCountry(Country $country, Request $request)
    {
        // reuse index() cho quốc gia
        $request->merge(['country' => $country->slug]);
        return $this->index($request);
    }
}
