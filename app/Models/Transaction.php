<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'national_id', 'type', 'amount',
    ];

    public function person() {
        return $this->belongsTo(Person::class, 'national_id', 'national_id');
    }
}
