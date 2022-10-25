<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'merchant_id',
        'id',
        'shipping_fees',
        'shipping_type'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
