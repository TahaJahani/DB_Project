<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    public $timestamps = false;
    protected $fillable = [
        'order_id', 'card_id', 'status', 'date', 'payment_method',
        'transaction_id', 'postman_national_id', 'address_id',
    ];

    public function address() {
        return $this->belongsTo(Address::class);
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function cart() {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function postman() {
        return $this->belongsTo(Postman::class, 'postman_national_id', 'national_id');
    }
}
