<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_id';

    protected $fillable = [
        'cart_id', 'national_id',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'national_id', 'national_id');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'cart_products', 'cart_id', 'product_id');
    }
}
