<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCoupon extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'coupon_id',
        'amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
