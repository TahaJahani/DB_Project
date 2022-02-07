<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Person
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'national_id';

    protected $fillable = [
        'national_id', 'company_name', 'average_score',
    ];

    public function person() {
        return $this->belongsTo(Person::class, 'national_id', 'national_id');
    }
}
