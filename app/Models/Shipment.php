<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'carrier',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'status',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /** Thuộc về một đơn hàng */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
