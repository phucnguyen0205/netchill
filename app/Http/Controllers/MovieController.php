<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('category');
    
        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }
    
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $genres = Genre::all();
        $countries = Country::all();
        $banners = Banner::all();
        $movies = Movie::inRandomOrder()->paginate(28);
        $latestMovies = $movies->sortByDesc('created_at')->take(6);
        $categories = Category::all();
    
        return view('movies.index', compact('movies', 'categories', 'genres', 'countries', 'banners', 'latestMovies'));
    }

    public function edit(Movie $movie)
    {
        $this->authorize('admin');
        $categories = Category::all();
        return view('movies.edit', compact('movie','categories'));
    }

    public function update(Request $request, Movie $movie)
    {
        $this->authorize('admin');

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'poster'      => 'nullable|image|max:10240', // Giới hạn poster lên 10MB
        ]);

        if ($request->hasFile('poster')) {
            if ($movie->poster) {
                Storage::disk('public')->delete($movie->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $movie->update($data);

        return redirect()
            ->route('movies.show', $movie->slug)
            ->with('success', 'Đã cập nhật phim thành công!');
    }

    public function destroy(Movie $movie)
    {
        $this->authorize('admin');

        if ($movie->poster) {
            Storage::disk('public')->delete($movie->poster);
        }

        if ($movie->file_name) {
            $videoPath = 'videos/' . $movie->file_name;
            if (Storage::disk('public')->exists($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }
        }

        $movie->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Xóa phim thành công');
    }

    public function detai($slug)
    {
        $movie = Movie::where('slug', $slug)->firstOrFail();
        return view('movies.detai', compact('movie'));
    }

    public function show($slug)
    {
        $movie = Movie::where('slug', $slug)->firstOrFail();
        
        // Sửa lỗi: Đảm bảo $movie->episodes không phải là null trước khi gọi first()
        $firstEpisode = $movie->episodes ? $movie->episodes->first() : null;
        
        return view('movies.show', compact('movie', 'firstEpisode'));
    }

    public function create()
    {
        $this->authorize('admin');
        $categories = Category::all();
        return view('movies.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('admin');

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'file_name'   => 'required|string',
        ]);
        
        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('public/posters');
            $data['poster'] = $posterPath;
        }

        $data['video_path'] = $data['file_name'];

        $movie = Movie::create($data);

        return redirect()
            ->route('movies.show', $movie->slug)
            ->with('success', 'Đã tạo phim thành công!');
    }
}
