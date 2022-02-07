<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $primaryKey = 'national_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $filleable = [
        'national_id', 'email', 'first_name', 'last_name',
        'telephone', 'mobile',
    ];

    public function bookmarks() {
        return $this->hasMany(Bookmark::class, 'national_id', 'national_id');
    }

    public function addresses() {
        return $this->hasMany(Address::class, 'national_id', 'national_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'national_id', 'national_id');
    }
}
