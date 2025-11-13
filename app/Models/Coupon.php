<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'per_user_limit',
        'starts_at',
        'ends_at',
        'min_order_total',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /** Đơn hàng đã sử dụng coupon */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_coupons')
            ->withPivot('amount');
    }

    /** Pivot record */
    public function orderCoupons()
    {
        return $this->hasMany(OrderCoupon::class);
    }

    /** Lịch sử sử dụng */
    public function redemptions()
    {
        return $this->hasMany(CouponRedemption::class);
    }
}
