<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /** Quan hệ: 1 cart có nhiều cart item */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /** Quan hệ: cart thuộc về 1 user (có thể null nếu guest -> khách vãng lai) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
