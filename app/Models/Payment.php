<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id','provider','txn_ref','order_code',
        'amount','currency','status','provider_txn','paid_at','meta',
    ];

    protected $casts = [
        'meta'   => 'array',
        'paid_at'=> 'datetime',
        'amount' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
