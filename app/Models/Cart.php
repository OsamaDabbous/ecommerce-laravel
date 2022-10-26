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
        'store_id'
    ];

    public function items(){
        return $this->belongsToMany(Product::class, 'cart_product','cart_id', 'product_id');
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }

}
