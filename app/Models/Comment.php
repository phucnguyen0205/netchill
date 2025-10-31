<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id','user_id','content','parent_id','is_secret'
    ];

    public function movie(){ return $this->belongsTo(Movie::class); }
    public function user(){ return $this->belongsTo(User::class); }

    public function parent(){ return $this->belongsTo(Comment::class,'parent_id'); }
    public function replies(){ return $this->hasMany(Comment::class,'parent_id')->latest(); }

    public function reactions(){ return $this->hasMany(CommentReaction::class); }
    public function votes(){ return $this->hasMany(\App\Models\CommentVote::class); }
    public function likesCount(){ return $this->reactions()->where('value',1); }
    public function dislikesCount(){ return $this->reactions()->where('value',-1); }

}
