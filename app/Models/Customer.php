<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Person
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'national_id';

    protected $fillable = [
        'national_id', 'loyalty_score',
    ];

    public function person() {
        return $this->belongsTo(Person::class, 'national_id', 'national_id');
    }

    public function cart() {
        return $this->hasMany(Cart::class, 'national_id', 'national_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'national_id', 'national_id');
    }
}
