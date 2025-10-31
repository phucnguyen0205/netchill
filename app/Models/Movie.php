<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
class Movie extends Model
{
    
    use HasFactory, Sluggable;

    protected $fillable = [
        'title','english_title','release_year','is_series','total_seasons',
        'version','age_rating','description','poster','banner',
        'file_name','video_path', // 👈 thêm vào đây
      ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    // app/Models/Movie.php
    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class);
    }

// Điểm TB trên thang 10 (mỗi sao = 2 điểm)
public function getAvgRatingAttribute(): float
{
    $avgStars = (float) $this->ratings()->avg('stars'); // 1..5
    return round($avgStars * 2, 1); // 0..10, 1 số lẻ
}
// app/Models/Movie.php
public function getRouteKeyName() {
    return 'slug';
}

public function getRatingsCountAttribute(): int
{
    return (int) $this->ratings()->count();
}
public function favoriters() // danh sách user đã thích movie này
{
    return $this->morphToMany(\App\Models\User::class, 'favoritable', 'favorites')->withTimestamps();
}
public function watchlists()
{
    return $this->belongsToMany(\App\Models\Watchlist::class, 'movie_watchlist', 'movie_id', 'watchlist_id')
                ->withTimestamps();
}
public function viewingProgresses() {
    return $this->hasMany(\App\Models\ViewingProgress::class);
}
public function categories()
{
    return $this->belongsToMany(\App\Models\Category::class, 'category_movie', 'movie_id', 'category_id');
}
public function countries()
{
    // Đổi 'country_movie' nếu pivot của bạn tên khác (xem Bước 3)
    return $this->belongsToMany(\App\Models\Country::class, 'country_movie', 'movie_id', 'country_id');
}
public function getAgeRatingKeyAttribute(): string
{
    $raw = strtoupper(trim((string) ($this->age_rating ?? '')));
    return match(true) {
        $raw === 'P' || $raw === 'K'        => 'P',
        $raw === 'T13' || $raw === '13'     => 'T13',
        $raw === 'T16' || $raw === '16'     => 'T16',
        $raw === 'T18' || $raw === '18'     => 'T18',
        default                             => 'T13',
    };
}

public function getAgeRatingLabelAttribute(): string
{
    return [
        'P' => 'P',
        'T13' => 'T13',
        'T16' => 'T16',
        'T18' => 'T18',
    ][$this->age_rating_key];
}

public function getAgeRatingBadgeClassAttribute(): string
{
    return [
        'P' => 'bg-success',
        'T13' => 'bg-secondary',
        'T16' => 'bg-secondary',
        'T18' => 'bg-secondary',
    ][$this->age_rating_key];
}

public function getAgeRatingTitleAttribute(): string
{
    return [
        'P' => 'Phổ cập – phù hợp mọi lứa tuổi',
        'T13' => 'Phù hợp từ 13 tuổi',
        'T16' => 'Phù hợp từ 16 tuổi',
        'T18' => 'Phù hợp từ 18 tuổi',
    ][$this->age_rating_key];
}
public function favorites()
{
    return $this->morphMany(Favorite::class, 'favoritable');
}
public function watchHistories()
{
    return $this->hasMany(ViewingProgress::class);
}
public function seasons()
    {
        return $this->hasMany(Season::class)->orderBy('season_number');
    }
    public function episodes()
    {
        return $this->hasManyThrough(Episode::class, Season::class);
    }
    public function banners()
    {
        return $this->hasMany(\App\Models\Banner::class);
    }
    public function showtimes()
{
    return $this->hasMany(\App\Models\Showtime::class)->orderBy('show_date')->orderBy('start_time');
}
public function scopeSeries($q)
{
    return $q->where('is_series', 1); // nếu bạn dùng cột 'type', đổi thành ->where('type','series')
}
}
