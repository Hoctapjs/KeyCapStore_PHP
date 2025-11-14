<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
    ];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag_pivot', 'product_tag_id', 'product_id');
    }
}
