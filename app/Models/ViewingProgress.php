<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewingProgress extends Model
{
    protected $table = 'viewing_progresses'; 
    protected $fillable = [
        'user_id','movie_id','last_position','duration','progress','last_watched_at','completed_at'
    ];

    protected $casts = [
        'last_watched_at' => 'datetime',
        'completed_at'    => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function movie(): BelongsTo { return $this->belongsTo(Movie::class); }
}
