<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postman extends Person
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'national_id';

    protected $fillable = [
        'national_id', 'salary',
    ];

    public function person() {
        return $this->belongsTo(Person::class, 'national_id', 'national_id');
    }
}
