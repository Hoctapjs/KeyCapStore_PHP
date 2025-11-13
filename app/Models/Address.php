<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = ['full_name', 'phone', 'address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country', 'is_default', 'user_id'];
    protected $casts = [
        'is_default' => 'boolean', // Đảm bảo trả về true/false
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
