<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Models\ViewingProgress;


class MovieController extends Controller
{public function index(Request $request)
{
    $query = Movie::query();

    if ($request->filled('search')) {
        $query->where('title', 'like', '%'.$request->search.'%');
    }

    if ($request->filled('category')) {
        $slug = $request->string('category')->toString();
        $query->whereHas('categories', fn($q) => $q->where('slug', $slug));
    }

    $movies = $query
    ->withAvg('ratings', 'stars')   // -> ratings_avg_stars
    ->withCount('ratings')          // -> ratings_count
    ->latest()
    ->paginate(28);


    $latestMovies = Movie::latest()->take(6)->get();
    $categories   = Category::all();
    $genres       = Genre::all();
    $countries    = Country::all();
    $banners = \App\Models\Banner::with('movie')
    ->where('variant', 'hero')
    ->latest()
    ->get()
    ->unique('movie_id')
    ->take(6);



    return view('movies.index', compact(
        'movies', 'categories', 'genres', 'countries', 'banners', 'latestMovies'
    ));
}


public function edit(Movie $movie)
{
    // Cần đảm bảo rằng mối quan hệ Seasons và Episodes của chúng được tải
    $movie->load([
        'categories', 
        'countries', 
        'banners',
        // 🔥 Đảm bảo tải Seasons và Episodes của từng Season
        'seasons.episodes' 
    ]);

    $categories = Category::all();
    $countries = Country::all();

    return view('movies.edit', compact('movie', 'categories', 'countries'));
}
public function update(Request $request, Movie $movie)
{
    $this->authorize('admin');

    $data = $request->validate([
        'title'           => ['required','string','max:255'],
        'description'     => ['nullable','string'],
        'english_title'   => ['nullable','string','max:255'], // MỚI: Thêm English Title
        'release_year'    => ['nullable','integer','min:1900','max:'.date('Y')], // MỚI: Thêm Release Year
        'version'         => ['required','in:sub,dub,raw'], // MỚI: Thêm Version
        'age_rating'      => ['nullable','in:P,K,T13,T16,T18'], // MỚI: Thêm Age Rating
        'is_series'       => ['required','in:0,1'],
        'total_seasons'   => ['nullable','integer','min:0'], // MỚI: Thêm Total Seasons

        'country_ids'     => ['nullable','array'], // MỚI: Thêm Country IDs
        'country_ids.*'   => ['integer','exists:countries,id'],

        'category_ids'    => ['required','array','min:1'],
        'category_ids.*'  => ['integer','exists:categories,id'],

        // nhiều ảnh khi cập nhật
        'posters'         => ['nullable','array'],
        'posters.*'       => ['image','mimes:jpg,jpeg,png,gif,webp','max:10240'],
        'banners'         => ['nullable','array'],
        'banners.*'       => ['image','mimes:jpg,jpeg,png,gif,webp','max:10240'],
        'delete_banners'  => ['nullable','array'],
        'delete_banners.*'=> ['integer','exists:banners,id'],

        // phim lẻ mới cần; phim bộ thì nullable
        'file_name'       => ['nullable','string'],

        // seasons & episodes (nếu phim bộ)
        'seasons'                         => ['nullable','array'],
        'seasons.*.number'               => ['nullable','integer','min:1'],
        'seasons.*.total_episodes'       => ['nullable','integer','min:1'],
        'seasons.*.note'                 => ['nullable','string','max:255'],
        'seasons.*.episodes'             => ['nullable','array'],
        'seasons.*.episodes.*.number'    => ['nullable','integer','min:1'],
        'seasons.*.episodes.*.title'     => ['nullable','string','max:255'],
        // Lấy video file name từ chunk upload (giống như store)
        'seasons.*.episodes.*.video_file_name' => ['nullable','string'],
        // 'seasons.*.episodes.*.video'     => ['nullable','file','mimetypes:video/mp4,video/mpeg','max:512000'], // Loại bỏ vì đã dùng chunk upload
    ]);

    $computedTotalSeasons = 0;
    if ((int)$data['is_series'] === 1) {
        $computedTotalSeasons = (int)($request->input('total_seasons', 0));
        if ($computedTotalSeasons <= 0 && !empty($data['seasons'])) {
            $computedTotalSeasons = collect($data['seasons'])
                ->filter(fn($s) => !empty($s['number']) || !empty($s['total_episodes']) || !empty($s['note']))
                ->count();
        }
    }
    $computedTotalSeasons = max(0, $computedTotalSeasons);
    $movie->total_seasons = $computedTotalSeasons; // ⬅️ đảm bảo không null

    // Phim lẻ phải có file_name (đã merge xong)
    if ((int)$data['is_series'] === 0 && !$request->filled('file_name') && !$movie->video_path) {
        return back()->withErrors(['file_name'=>'Vui lòng upload video cho Phim lẻ hoặc để lại video cũ.'])->withInput();
    }

    // upload nhiều ảnh mới (nếu có)
    $posterPaths = [];
    $bannerPaths = [];
    if ($request->hasFile('posters')) {
        foreach ($request->file('posters') as $f) $posterPaths[] = $f->store('posters','public');
    }
    if ($request->hasFile('banners')) {
        foreach ($request->file('banners') as $f) $bannerPaths[] = $f->store('banners','public');
    }

    // cập nhật movie cơ bản
    $movie->fill([
        'title'           => $data['title'],
        'english_title'   => $data['english_title'] ?? null,
        'release_year'    => $data['release_year'] ?? null,
        'version'         => $data['version'],
        'age_rating'      => $data['age_rating'] ?? null,
        'description'     => $data['description'] ?? null,
        'is_series'       => (int)$data['is_series'],
    ]);

    // poster chính từ poster đầu (nếu có)
    if (!empty($posterPaths[0])) $movie->poster = $posterPaths[0];

    if ((int)$data['is_series'] === 0) {
        // PHIM LẺ: gán file_name & video_path
        $movie->file_name  = $data['file_name'] ?? $movie->file_name;
        $movie->video_path = !empty($data['file_name']) ? ('videos/'.$data['file_name']) : $movie->video_path;
    } else {
        // PHIM BỘ: không có video chính
        $movie->file_name  = null;   // cần cột nullable (bước 1)
        $movie->video_path = null;   // cần cột nullable (bước 1)
    }

    $movie->save();

    // sync category & countries
    $movie->categories()->sync($data['category_ids']);
    $movie->countries()->sync($data['country_ids'] ?? []); // Thêm sync countries

    // xoá banners được tick
    if (!empty($data['delete_banners'])) {
        Banner::whereIn('id', $data['delete_banners'])
              ->where('movie_id', $movie->id)
              ->delete();
    }

    // thêm banners từ poster phụ & banners mới
    $bulk = [];
    if (count($posterPaths) > 1) {
        foreach (array_slice($posterPaths, 1) as $p) {
            $bulk[] = [
                'movie_id'=>$movie->id,'image_path'=>$p,'variant'=>'poster',
                'title'=>$movie->title,'description'=>\Illuminate\Support\Str::limit($movie->description ?? '',160),
                'created_at'=>now(),'updated_at'=>now(),
            ];
        }
    }
    foreach ($bannerPaths as $b) {
        $bulk[] = [
            'movie_id'=>$movie->id,'image_path'=>$b,'variant'=>'hero',
            'title'=>$movie->title,'description'=>\Illuminate\Support\Str::limit($movie->description ?? '',160),
            'created_at'=>now(),'updated_at'=>now(),
        ];
    }
    if ($bulk) Banner::insert($bulk);

    // ==== seasons/episodes cho phim bộ (LOGIC CẬP NHẬT/THÊM MỚI TỪ FORM) ====
    if ((int)$data['is_series'] === 1) {
        if (!empty($data['seasons'])) {
            $seasonIdsToKeep = []; 

            foreach ($data['seasons'] as $sIdx => $s) {
                if (empty($s['number']) && empty($s['total_episodes']) && empty($s['note'])) continue;

                $seasonNumber = (int)($s['number'] ?? 1);

                // Cập nhật hoặc tạo Season
                $season = $movie->seasons()->updateOrCreate(
                    ['season_number' => $seasonNumber], 
                    [
                        'title'          => 'Season ' . $seasonNumber,
                        'total_episodes' => (int)($s['total_episodes'] ?? 0),
                        'note'           => $s['note'] ?? null,
                    ]
                );
                $seasonIdsToKeep[] = $season->id; 

                // Episodes
                $episodeIdsToKeep = []; 

                foreach (($s['episodes'] ?? []) as $eKey => $eData) {
                    $episodeNumber = (int)($eData['number'] ?? ($eKey + 1));
                    $epTitle = $eData['title'] ?? null;
                    $fileName = $eData['video_file_name'] ?? null; 

                    // Cập nhật hoặc tạo Episode (chưa bao gồm video info)
                    $ep = $season->episodes()->updateOrCreate(
                        ['episode_number' => $episodeNumber],
                        ['title' => $epTitle]
                    );

                    $episodeIdsToKeep[] = $ep->id; 

                    // Xử lý Video (chỉ cập nhật nếu có file_name mới được gửi lên)
                    if ($fileName) { 
                        $ep->file_name  = $fileName;
                        $ep->video_path = 'videos/' . $fileName; 
                        $ep->save();
                    }
                }
                
                // 🔥 (Tuỳ chọn) Xoá Episodes cũ không có trong form
                // $season->episodes()->whereNotIn('id', $episodeIdsToKeep)->delete(); 
            }

            // 🔥 (Tuỳ chọn) Xoá Seasons cũ không có trong form
            // $movie->seasons()->whereNotIn('id', $seasonIdsToKeep)->delete(); 

        } else {
            // Xử lý khi user xoá hết seasons trên form
            // (Hiện tại giữ nguyên dữ liệu cũ nếu form không gửi lên,
            // nếu muốn xoá thì cần uncomment logic delete ở trên và dưới)
            // $movie->seasons()->delete();
        }
    }
    
    return redirect()->route('movies.edit',$movie)->with('success','Cập nhật phim thành công!');
}
public function stream(Movie $movie, Request $request) // 🔥 Cần thêm Request
{
    $rel = $movie->video_path ?: ($movie->file_name ? 'videos/'.$movie->file_name : null);
    abort_unless($rel, 404);

    $fullPath = storage_path('app/public/' . ltrim($rel, '/'));
    abort_unless(is_file($fullPath), 404);

    // 🔥 Truyền $request vào streamFile
    return $this->streamFile($fullPath, 'video/mp4', $request);
}

/**
 * Stream file với hỗ trợ Range 206
 */
protected function streamFile(string $path, string $mime, Request $request)
{
    $size  = filesize($path);
    $start = 0;
    $end   = $size - 1;

    // 🔥 Sử dụng Request object để lấy header, an toàn và dễ test hơn
    $rangeHeader = $request->header('Range'); 
    
    // Parse Range header
    if ($rangeHeader) {
        if (preg_match('/bytes=(\d*)-(\d*)/i', $rangeHeader, $m)) {
            if ($m[1] !== '') $start = intval($m[1]);
            if ($m[2] !== '') $end   = intval($m[2]);
            if ($end >= $size) $end = $size - 1;
            if ($start > $end) $start = 0;
        }
    }

    $length   = $end - $start + 1;
    $status   = ($start > 0 || $end < $size - 1) ? 206 : 200;
    $headers  = [
        'Content-Type'        => $mime,
        'Content-Length'      => $length,
        'Accept-Ranges'       => 'bytes',
        'Content-Range'       => "bytes $start-$end/$size",
        'Cache-Control'       => 'public, max-age=0',
        'Content-Disposition' => 'inline; filename="'.basename($path).'"',
    ];

    $stream = function() use ($path, $start, $end) {
        $chunk = 1024 * 1024; // 1MB
        $fh = fopen($path, 'rb');
        fseek($fh, $start);
        $bytesToOutput = $end - $start + 1;
        while ($bytesToOutput > 0 && !feof($fh)) {
            $read = ($bytesToOutput > $chunk) ? $chunk : $bytesToOutput;
            echo fread($fh, $read);
            flush();
            $bytesToOutput -= $read;
        }
        fclose($fh);
    };

    return response()->stream($stream, $status, $headers);
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

        // 🔥 Cần xoá cả seasons/episodes và file video của chúng khi xoá phim
        if ((int)$movie->is_series === 1) {
            $movie->seasons()->each(function ($season) {
                $season->episodes()->each(function ($episode) {
                    if ($episode->file_name) {
                        $videoPath = 'videos/' . $episode->file_name;
                        if (Storage::disk('public')->exists($videoPath)) {
                            Storage::disk('public')->delete($videoPath);
                        }
                    }
                    $episode->delete();
                });
                $season->delete();
            });
        }

        $movie->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Xóa phim thành công');
    }
    
    public function streamEpisode(Episode $episode, Request $request) // 🔥 SỬA ĐỔI: Thêm $fullPath calculation
{
    $rel = $episode->video_path;
    if (!$rel && $episode->file_name) {
        $rel = 'videos/'.$episode->file_name; 
    }
    
    abort_unless($rel, 404);
   
    // Chuẩn hoá đường dẫn
    if ($rel && str_starts_with($rel, 'storage/')) {
        $rel = substr($rel, strlen('storage/')); 
    }
    if ($rel && preg_match('#^https?://#', $rel)) {
        return redirect()->away($rel);
    }

    // 🔥 THÊM: Tính toán đường dẫn tuyệt đối
    $fullPath = storage_path('app/public/' . ltrim($rel, '/'));

    abort_unless(is_file($fullPath), 404);
    
    // Truyền $request vào streamFile
    return $this->streamFile($fullPath, 'video/mp4', $request);
}
    
    public function detai($slug)
    {
        $movie = Movie::where('slug', $slug)
            ->withAvg('ratings', 'stars')   // => ratings_avg_stars cho Blade
            ->withCount('ratings')          // => ratings_count cho Blade
            ->with(['ratings' => fn($q) => $q->where('user_id', auth()->id())]) // sao của user hiện tại
            ->firstOrFail();
    
        // nếu view detai.blade.php không dùng, có thể bỏ biến này
        $initialTime = 0;
    
        return view('movies.detai', compact('movie','initialTime'));
    }
    
    public function create()
    {
        $categories = Category::orderBy('name')->get(['id','name']);
        $genres     = Genre::orderBy('name')->get(['id','name']);
        $countries  = Country::orderBy('name')->get(['id','name']);

        return view('movies.create', compact('categories','genres','countries'));
    }
    public function show(\App\Models\Movie $movie)
    {
        $movie->load([
            'banners' => fn($q)=>$q->select('banners.*')
                                   ->where('banners.movie_id',$movie->id)
                                   ->latest('banners.created_at'),
            'categories:id,name,slug',
            'countries:id,name,slug',
            // 🔥 TẢI TẤT CẢ SEASONS VÀ EPISODES (EAGER LOAD)
            'seasons' => fn($q)=>$q->select('seasons.id','seasons.movie_id','seasons.season_number','seasons.total_episodes')
                                   ->orderBy('seasons.season_number'),
            'seasons.episodes' => fn($q)=>$q->select(
                'episodes.id','episodes.season_id','episodes.title',
                'episodes.file_name','episodes.video_path','episodes.episode_number'
            )->orderBy('episodes.episode_number'),
            'ratings' => fn($q)=>$q->with(['user:id,name,email'])->latest('ratings.created_at'),
        ]);
    
        $movie->loadAvg('ratings as ratings_avg_stars', 'stars')
              ->loadCount('ratings');
    
        // ====== TÌM TẬP ĐẦU TIÊN (DÙNG EAGER LOAD ĐÃ CÓ) ======
        $firstEpisode = null;
        if ((int)$movie->is_series === 1) {
            // 1. Lấy Season đầu tiên từ collection đã Eager Load
            $firstSeason = $movie->seasons
                ->sortBy('season_number')
                ->first();
            
            if ($firstSeason) {
                // 2. Lấy Episode đầu tiên có video/file_name từ collection đã Eager Load
                $firstEpisode = $firstSeason->episodes
                    ->sortBy('episode_number') // Sắp xếp theo số tập để đảm bảo lấy TẬP 1
                    ->first(function ($episode) {
                        // Điều kiện quan trọng: Tập phải có video_path HOẶC file_name
                        return !empty($episode->video_path) || !empty($episode->file_name);
                    });
            }
        }
    
        // ====== TẠO PLAY URL VÀ CÁC BIẾN KHÁC ======
        $currentList       = collect();
        $plannedOfCurrent  = 0;
        $playUrl = null;
    
        if ((int)$movie->is_series === 1) {
            // Phim bộ: Dùng episode đầu tiên vừa tìm được
            // Giả định route cho episode là 'episodes.show'
            $playUrl = $firstEpisode ? route('episodes.show', $firstEpisode) : null;
            
            $currentSeason      = $movie->seasons->first();
            $currentList        = $currentSeason?->episodes ?? collect();
            $plannedOfCurrent   = (int)($currentSeason?->total_episodes ?? 0);
        } else {
            // Phim lẻ: Luôn có URL nếu Movie có file_name/video_path
            if (!empty($movie->file_name) || !empty($movie->video_path)) {
                $playUrl = route('movies.detai', $movie->slug); 
            }
            
        }
        
        // Gallery (giữ nguyên code gom $galleryItems của bạn)
        $galleryItems = collect();
        if ($movie->poster) {
            $galleryItems->push(['type'=>'poster','url'=>\Storage::url($movie->poster)]);
        }
        foreach ($movie->banners as $b) {
            $galleryItems->push(['type'=>$b->variant ?? 'hero','url'=>\Storage::url($b->image_path)]);
        }
    
        return view('movies.show', [
            'movie'            => $movie,
            'firstEpisode'     => $firstEpisode, 
            'currentList'      => $currentList,
            'plannedOfCurrent' => $plannedOfCurrent,
            'galleryItems'     => $galleryItems,
            'playUrl'          => $playUrl, 
          ]);
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'english_title'  => 'nullable|string|max:255',
            'release_year'   => 'nullable|integer|min:1900|max:'.date('Y'),
            'is_series'      => 'required|in:0,1',
            'total_seasons'  => 'nullable|integer|min:1',
            'version'        => 'required|in:sub,dub,raw',
            'age_rating'     => 'nullable|in:P,K,T13,T16,T18',
            'description'    => 'nullable|string',
        
            // nhiều ảnh:
            'posters'        => 'nullable|array',
            'posters.*'      => 'image|max:4096',
            'banners'        => 'nullable|array',
            'banners.*'      => 'image|max:6144',
        
            // Phim lẻ mới cần file_name; Phim bộ thì nullable
            'file_name'      => 'nullable|string',
        
            'category_ids'   => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'country_ids'    => 'nullable|array',
            'country_ids.*'  => 'exists:countries,id',
        
            // seasons
            'seasons'                     => 'nullable|array',
            'seasons.*.number'           => 'nullable|integer|min:1',
            'seasons.*.total_episodes'   => 'nullable|integer|min:1',
            'seasons.*.note'             => 'nullable|string|max:255',

            'seasons.*.episodes'             => 'nullable|array',
            'seasons.*.episodes.*.number'    => ['nullable','integer','min:1'],
            'seasons.*.episodes.*.title'     => ['nullable','string','max:255'],
            'seasons.*.episodes.*.video_file_name' => 'nullable|string',
        ]);
        
        // Ràng buộc bổ sung: nếu is_series=0 (Phim lẻ) thì file_name phải có
        if ((int)$data['is_series'] === 0 && !$request->filled('file_name')) {
            return back()->withErrors(['file_name'=>'Vui lòng upload video cho Phim lẻ.'])->withInput();
        }
          
        $posterPaths = [];
        $bannerPaths = [];
        
        if ($request->hasFile('posters')) {
            foreach ($request->file('posters') as $f) {
                $posterPaths[] = $f->store('posters', 'public');
            }
        }
        
        if ($request->hasFile('banners')) {
            foreach ($request->file('banners') as $f) {
                $bannerPaths[] = $f->store('banners', 'public');
            }
        }
        $computedTotalSeasons = 0;
        if ((int)$data['is_series'] === 1) {
            // ưu tiên giá trị nhập form, nếu trống thì đếm theo mảng seasons
            $computedTotalSeasons = (int)($data['total_seasons'] ?? 0);
            if ($computedTotalSeasons <= 0 && !empty($data['seasons'])) {
                // đếm season hợp lệ (có number hoặc có dữ liệu)
                $computedTotalSeasons = collect($data['seasons'])
                    ->filter(fn($s) => !empty($s['number']) || !empty($s['total_episodes']) || !empty($s['note']))
                    ->count();
            }
        }
        $computedTotalSeasons = max(0, $computedTotalSeasons);
        $movie = Movie::create([
            'title'         => $data['title'],
            'english_title' => $data['english_title'] ?? null,
            'release_year'  => $data['release_year'] ?? null,
            'is_series'     => (int)$data['is_series'],
            'total_seasons' => $computedTotalSeasons, // ⬅️ không còn null
            'version'       => $data['version'],
            'age_rating'    => $data['age_rating'] ?? null,
            'description'   => $data['description'] ?? null,
            'poster'        => $posterPaths[0] ?? null,
            'file_name'     => (int)$data['is_series'] === 0 ? ($data['file_name'] ?? null) : null,
            'video_path'    => (int)$data['is_series'] === 0 && !empty($data['file_name'])
                                ? 'videos/'.$data['file_name']
                                : null,
        ]);
        // Gắn categories/countries
        $movie->categories()->sync($data['category_ids'] ?? []);
        $movie->countries()->sync($data['country_ids'] ?? []);
          
        if (count($posterPaths) > 1) {
            $bulk = [];
            foreach (array_slice($posterPaths, 1) as $p) {
                $bulk[] = [
                    'image_path'  => $p,
                    'variant'     => 'poster',
                    'title'       => $data['title'],
                    'description' => \Illuminate\Support\Str::limit($data['description'] ?? '', 160),
                ];
            }
            if ($bulk) $movie->banners()->createMany($bulk);
        }
        if ($bannerPaths) {
            $bulk = [];
            foreach ($bannerPaths as $b) {
                $bulk[] = [
                    'image_path'  => $b,
                    'variant'     => 'hero', // bạn có thể phân loại 'hero','mobile','extra'...
                    'title'       => $data['title'],
                    'description' => \Illuminate\Support\Str::limit($data['description'] ?? '', 160),
                ];
            }
            $movie->banners()->createMany($bulk);
        }     
        
        if ((int)$data['is_series'] === 1 && !empty($data['seasons'])) {
            foreach ($data['seasons'] as $sIdx => $s) {
                if (empty($s['number']) && empty($s['total_episodes']) && empty($s['note'])) continue;
        
                // 🔥 Đảm bảo Season được tạo/tìm thấy (SỬ DỤNG season_number làm key)
                $season = $movie->seasons()->updateOrCreate(
                    ['season_number' => (int)($s['number'] ?? 1)],
                    [
                        'title'          => 'Season '.((int)($s['number'] ?? 1)),
                        'total_episodes' => (int)($s['total_episodes'] ?? 0),
                        'status'         => 'draft',
                        'published_at'   => now(),
                        'note'           => $s['note'] ?? null,
                    ]
                );
        
                // Episodes (lưu video từng tập nếu có)
                foreach (($s['episodes'] ?? []) as $eKey => $eData) {
                    $ep = $season->episodes()->updateOrCreate(
                        ['episode_number' => (int)($eData['number'] ?? ($eKey+1))],
                        ['title' => $eData['title'] ?? null]
                    );
                
                    // 1) Nếu dùng chunk upload cho tập: nhận file_name từ hidden
                    if (!empty($eData['video_file_name'])) { 
                        $fileName = $eData['video_file_name'];
                        $ep->file_name  = $fileName;
                        $ep->video_path = 'videos/'.$fileName; 
                        $ep->save();
                        continue; 
                    }
        
                    // 2) (tuỳ chọn) Nếu vẫn cho phép upload trực tiếp (không chunk)
                    if (!empty($eData['video']) && $eData['video'] instanceof \Illuminate\Http\UploadedFile) {
                        $path = $eData['video']->store('episodes', 'public'); 
                        $ep->file_name  = basename($path);
                        $ep->video_path = $path;
                        $ep->save();
                    }
                    if (!empty($eData['delete'])) {
                        // (tuỳ bạn) xoá file vật lý nếu muốn
                        $ep->delete();
                    }
                }
            }
        }
        
        return redirect()
            ->route('movies.edit', $movie)
            ->with('success', 'Phim mới đã được tạo thành công!');   
    }
}
