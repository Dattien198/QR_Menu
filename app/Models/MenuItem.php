<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'cost',
        'images',
        'description',
        'variants',
        'tags',
        'calories',
        'preparation_time',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'images' => 'json',
        'variants' => 'json',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function addons()
    {
        return $this->hasMany(Addon::class);
    }
}
