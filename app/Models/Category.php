<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // ✅ Thêm dòng này


class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug']; // ✅ Thêm slug để fillable

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'category_movie', 'category_id', 'movie_id')
                    ->withTimestamps();
    }
    

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
