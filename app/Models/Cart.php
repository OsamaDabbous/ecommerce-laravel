<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'total',
    ];

    public function items(){
        return $this->belongsToMany(Product::class, 'cart_product','cart_id', 'product_sku');
    }
    // public function items()
    // {
    //     return $this->belongsToMany('App\Models\Reservation', 'customer_reservation', 'customer_id', 'product_sku');
    // }
}
