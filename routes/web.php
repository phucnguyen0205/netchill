<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\CountryController;

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
Route::get('/movies/stream/{movie:slug}', [StreamController::class, 'stream'])
    ->name('movies.stream');

// Group for Authenticated Users (Dành cho người dùng đã đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/movies/{movie:slug}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
});

// Group for Admin Routes (Dành cho quản trị viên)
Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('movies', MovieController::class)->except(['show']);
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show'])->names('categories');
    Route::resource('genres', GenreController::class)->except(['show'])->names('genres');
    Route::resource('countries', CountryController::class)->except(['show'])->names('countries');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/upload-chunk', [VideoController::class, 'uploadChunk'])->name('upload.chunk');
    Route::post('/merge-chunks', [VideoController::class, 'mergeChunks'])->name('upload.merge');
});

require __DIR__.'/auth.php';
