<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'national_id', 'product_id', 'has_bought', 'score',
        'text', 'date', 'status',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'national_id', 'national_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
