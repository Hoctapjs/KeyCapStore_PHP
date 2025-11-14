<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'website_url',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
