<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'name',
        'image',
        'order_index',
        'parent_id',
        'display_hours',
    ];

    protected $casts = [
        'display_hours' => 'json',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
