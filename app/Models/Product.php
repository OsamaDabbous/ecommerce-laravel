<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'price',
        'tax_included',
        'store_id'
    ];

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product', 'product_sku','cart_id');
    }
}
