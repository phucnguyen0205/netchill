<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\WatchHistoryController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\VnPayController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CommentFeedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PaymentController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Đây là nơi bạn có thể đăng ký các route cho ứng dụng web của mình.
| Các route này được tải bởi RouteServiceProvider trong một nhóm
| chứa nhóm middleware "web".
|
*/

// Public Routes (Dành cho mọi người dùng)
Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Trang chi tiết phim (dẫn đến detai.blade.php)
// Khi người dùng truy cập /movies/ten-phim
Route::get('/movies/{movie:slug}', [MovieController::class, 'detai'])->name('movies.detai');

// Trang xem phim (dẫn đến show.blade.php)
// Khi người dùng truy cập /watch/ten-phim
Route::get('/watch/{movie:slug}', [MovieController::class, 'show'])->name('movies.show');


// Group for Authenticated Users (Dành cho người dùng đã đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/movies/{movie:slug}/rate', [RatingController::class, 'store'])
    ->middleware('auth')
    ->name('movies.ratings.store');
});
// PUBLIC
Route::get('/danh-muc', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/the-loai/{category:slug}', [CatalogController::class, 'byCategory'])->name('catalog.byCategory');
Route::get('/quoc-gia/{country:slug}', [CatalogController::class, 'byCountry'])->name('catalog.byCountry');

// Bình luận (đã đăng nhập)
Route::middleware('auth')->group(function () {
    // STORE: cần movie theo slug
    Route::post('/movies/{movie:slug}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    // UPDATE / DESTROY theo id comment
    Route::put('/comments/{comment}',    [CommentController::class,'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class,'destroy'])->name('comments.destroy');

    // React / Vote
    Route::post('/comments/vote',              [CommentController::class, 'vote'])->name('comments.vote');
    Route::post('/comments/{comment}/react',   [CommentController::class,'react'])->name('comments.react');
});

Route::middleware('auth')->get('/notifications/go/{notification}', function ($id) {
    $n = auth()->user()->notifications()->findOrFail($id);
    $n->markAsRead();
    return redirect()->to($n->data['url'] ?? '/');
})->name('notifications.go');
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [UserNotificationController::class,'index'])
        ->name('notifications.index');

    Route::post('/notifications/{notification}/read', [UserNotificationController::class,'read'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [UserNotificationController::class,'readAll'])
        ->name('notifications.read_all');
});
Route::middleware('auth')->group(function () {
    // Trang Tài khoản (đã có)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    // Các route mới
    Route::resource('watchlists', WatchlistController::class);

    Route::get('/profile/favourites', [ProfileController::class, 'favourites'])->name('profile.favourites');
    Route::get('/profile/watchlist', [ProfileController::class, 'watchlist'])->name('profile.watchlist');
    Route::get('/watchlists', [WatchlistController::class, 'index'])->name('watchlists.index');
    Route::post('/watchlists', [WatchlistController::class, 'store'])->name('watchlists.store');
    Route::post('/watchlists/{watchlist}/items', [WatchlistController::class, 'addItem'])
     ->name('watchlists.items.store');
    Route::post('/watchlists/quick-create', [\App\Http\Controllers\WatchlistController::class, 'quickCreateAndAttach'])
    ->name('watchlists.quick_create');
    Route::patch('/watchlists/{watchlist}', [WatchlistController::class, 'update'])->name('watchlists.update');
    Route::delete('/watchlists/{watchlist}/items/{movie}', [WatchlistController::class, 'removeItem'])
    ->name('watchlists.items.destroy');
Route::delete('/watchlists/{watchlist}/items/{movie}', [WatchlistController::class,'removeItem'])->middleware('auth');
Route::get('episodes/{episode}/stream', [MovieController::class, 'streamEpisode'])
    ->name('episodes.stream');
Route::get('/episodes/{episode}', [EpisodeController::class, 'show'])
    ->name('episodes.show');
    Route::get('/profile/history', [ProfileController::class, 'history'])->name('profile.history');
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
Route::get('/schedule/json', [ScheduleController::class, 'json'])->name('schedule.json');
});
Route::get('/movies/{movie:slug}/stream', [MovieController::class, 'stream'])
    ->name('movies.stream');
    Route::get('/comments/feed', [CommentFeedController::class, 'feed'])
    ->name('comments.feed');
    Route::get('/search/suggest', [SearchController::class, 'suggest'])
     ->name('search.suggest'); 
    // Route lấy số tập phim kế tiếp (phim bộ)
Route::get('admin/movies/{movie}/next-episode', [MovieController::class, 'getNextEpisode'])->name('admin.movies.next_episode');
Route::middleware('auth')->group(function () {
    Route::get('/profile/favourites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites',          [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites',        [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle',   [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
Route::middleware('auth')->group(function () {
    Route::post('/watch/{movie:slug}/progress', [WatchHistoryController::class, 'update'])
        ->name('watch.progress');
    Route::get('/profile/history', [WatchHistoryController::class, 'index'])
        ->name('profile.history');
});

Route::middleware('auth')->group(function () {
    Route::get('/wallet/topup', [\App\Http\Controllers\VnPayController::class, 'showTopUp'])->name('wallet.topup');
    Route::post('/wallet/topup', [\App\Http\Controllers\VnPayController::class, 'createPayment'])->name('wallet.topup.create');
    Route::get('/wallet/vnpay-return', [\App\Http\Controllers\VnPayController::class, 'vnpayReturn'])->name('wallet.vnpay.return');
    Route::post('/wallet/vnpay-ipn', [\App\Http\Controllers\VnPayController::class, 'vnpayIpn'])->name('wallet.vnpay.ipn');
});
Route::middleware('auth')->group(function () {
    // Bấm "Nâng cấp ngay" -> tạo Payment & redirect sang VNPAY
    Route::post('/premium/upgrade', [PremiumController::class, 'upgrade'])
    ->name('premium.upgrade');
});
Route::middleware(['web','auth'])->group(function () {
    Route::post('/topup', [PaymentController::class, 'topup'])
        ->name('payment.topup'); // 👈 tên phải đúng như trong Blade
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/account/upgrade', [PremiumController::class, 'upgrade'])
        ->name('premium.upgrade');
    Route::post('/account/downgrade', [PremiumController::class, 'downgrade'])
        ->name('premium.downgrade'); // tuỳ chọn, để hạ cấp
        Route::get('/premium/pricing', [PremiumController::class, 'pricing'])
        ->name('premium.pricing');
        Route::post('/premium/activate', [PremiumController::class, 'upgrade'])->name('premium.upgrade'); // bấm chọn gói -> set premium
});


Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('movies', MovieController::class)->except(['show']);
    
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show'])->names('categories');
    Route::resource('genres', GenreController::class)->except(['show'])->names('genres');
    Route::resource('countries', CountryController::class)->except(['show'])->names('countries');
    Route::get('/stats', [\App\Http\Controllers\Admin\StatController::class,'index'])->name('admin.stats.index');

    Route::resource('actors', \App\Http\Controllers\Admin\ActorController::class)->except(['show']);
    Route::resource('showtimes', \App\Http\Controllers\Admin\ShowtimeController::class)
    ->names('admin.showtimes');
    // Trang liệt kê theo năm
    Route::get('/years', [\App\Http\Controllers\Admin\YearController::class,'index'])->name('years.index');
    Route::post('/upload-chunk', [VideoController::class, 'uploadChunk'])->name('upload.chunk');
    Route::post('/merge-chunks', [VideoController::class, 'mergeChunks'])->name('upload.merge');
});

require __DIR__.'/auth.php';
