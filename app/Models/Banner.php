<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['movie_id', 'image_path', 'variant', 'title', 'description'];

    public function movie()
    {
        return $this->belongsTo(\App\Models\Movie::class);
    }
}

