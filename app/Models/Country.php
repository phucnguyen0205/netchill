<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    
  // app/Models/Country.php
public function movies()
{
    return $this->belongsToMany(\App\Models\Movie::class, 'country_movie', 'country_id', 'movie_id');
}

public function getRouteKeyName(): string
{
    return 'slug'; // nếu bạn route theo slug
}

}