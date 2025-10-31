<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'season_id','episode_number','title','description','duration',
        'status','published_at','file_name','video_path'
    ];

    public function season(){ return $this->belongsTo(Season::class); }
    public function files(){ return $this->hasMany(EpisodeFile::class); }
    public function movie() {
        return $this->belongsTo(\App\Models\Movie::class, 'ten_khoa_ngoai_thuc_te'); // ví dụ 'film_id'
    }
}
