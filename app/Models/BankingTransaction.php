<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingTransaction extends Transaction
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'bank_name', 'shaparak_code', 'transaction_id'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
