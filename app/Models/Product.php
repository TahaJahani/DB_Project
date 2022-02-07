<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primary_key = 'product_id';

    protected $fillable = [
        'product_id', 'name', 'description', 'quantity',
        'sale_price', 'purchase_price', 'discrount', 'img_url',
        'category_id'
    ];

    public $timestamps = false;

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function properties() {
        return $this->hasMany(Property::class, 'product_id', 'product_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'product_id', 'product_id');
    }
}
