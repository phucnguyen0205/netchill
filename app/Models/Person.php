<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'birthday',
        'photo',
    ];

    // Người có thể được nhiều user yêu thích
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    // Ví dụ: Nếu bạn có phim mà person tham gia
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_person');
    }
}
