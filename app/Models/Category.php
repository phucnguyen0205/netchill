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
        return $this->hasMany(Movie::class);
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
