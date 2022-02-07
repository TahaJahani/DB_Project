<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableProduct extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'product_date', 'expiration_date',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
