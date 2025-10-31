<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Favorite extends Model
{
    protected $table = 'favorites';
    protected $guarded = []; // hoặc chỉ định $fillable nếu bạn muốn

    public function favoritable()
    {
        return $this->morphTo();
    }
}

