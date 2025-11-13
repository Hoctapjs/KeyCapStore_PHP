<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'status',
        'transaction_id',
        'raw_payload',
        'paid_at'
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'paid_at' => 'datetime',
    ];

    /** thuộc về đơn hàng */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
