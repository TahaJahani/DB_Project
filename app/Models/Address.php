<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'national_id', 'postal_code', 'province', 'city', 'rest'
    ];

    public function person() {
        return $this->belongsTo(Person::class, 'national_id', 'national_id');
    }
}
