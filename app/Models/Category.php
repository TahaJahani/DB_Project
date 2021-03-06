<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_id', 'name', 'parent_category_id',
    ];

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_category_id', 'id');
    }
}
