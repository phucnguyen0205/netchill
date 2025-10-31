<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    // app/Models/Watchlist.php
protected $fillable = ['user_id','name'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function movies()
{
    return $this->belongsToMany(Movie::class, 'movie_watchlist')
                ->withTimestamps();
}

}
