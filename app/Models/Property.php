<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id', 'key', 'value'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
