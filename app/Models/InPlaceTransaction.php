<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InPlaceTransaction extends Transaction
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'transaction_id';
    public $incrementing = false;

    protected $fillable = [
        'transaction_id', 'method',
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
