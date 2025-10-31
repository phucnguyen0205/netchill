<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class EpisodeFile extends Model
{
    protected $fillable = ['episode_id','label','quality','format','path','is_default'];

    public function episode(){ return $this->belongsTo(Episode::class); }
}