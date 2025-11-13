<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'status',
        'subtotal',
        'discount_total',
        'shipping_fee',
        'tax_total',
        'total',
        'shipping_address',
        'billing_address',
        'note',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    /** Người dùng */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Sản phẩm trong đơn */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Thanh toán */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /** Lô hàng */
    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    /** Coupon đã dùng */
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'order_coupons')
            ->withPivot('amount');
    }

    /** Record pivot order_coupons */
    public function orderCoupons()
    {
        return $this->hasMany(OrderCoupon::class);
    }
}
