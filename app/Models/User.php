<?php

namespace App\Models;

use App\Models\Favorite;            // <— thêm import này nếu bạn dùng Favorite::class
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;          // <— chỉ import 1 lần
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;     // <— chỉ dùng 1 lần

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_premium' => 'boolean',
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteMovies()
    {
        return $this->morphedByMany(\App\Models\Movie::class, 'favoritable', 'favorites')
            ->withTimestamps()
            ->withPivot('created_at')
            ->orderBy('favorites.created_at', 'desc');
    }

    public function favoritePeople()
    {
        return $this->morphedByMany(\App\Models\Person::class, 'favoritable', 'favorites')
            ->withTimestamps()
            ->withPivot('created_at')
            ->orderBy('favorites.created_at', 'desc');
    }

    // helpers
    public function hasFavorited(\Illuminate\Database\Eloquent\Model $model): bool
    {
        return Favorite::where('user_id', $this->id)
            ->where('favoritable_id', $model->getKey())
            ->where('favoritable_type', $model->getMorphClass())
            ->exists();
    }

    public function hasFavoritedModel($model): bool
    {
        return Favorite::where('user_id', $this->id)
            ->where('favoritable_id', $model->getKey())
            ->where('favoritable_type', $model->getMorphClass())
            ->exists();
    }
    public function viewingProgresses() {
        return $this->hasMany(\App\Models\ViewingProgress::class);
    }
    public function payments()
{
    return $this->hasMany(\App\Models\Payment::class);
}
}
