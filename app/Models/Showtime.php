<?php

// app/Models/Showtime.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Showtime extends Model
{
    protected $fillable = [
        'movie_id','show_date','start_time','end_time','room','is_premiere','note'
    ];

    protected $casts = [
        'show_date'   => 'date',
        'start_time'  => 'datetime:H:i',
        'end_time'    => 'datetime:H:i',
        'is_premiere' => 'boolean',
    ];

    public function movie(): BelongsTo {
        return $this->belongsTo(Movie::class);
    }
}

