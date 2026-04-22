<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'name',
        'address',
        'phone',
        'manager_id',
        'is_active',
        'opening_time',
        'closing_time',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tables()
    {
        return $this->hasMany(RestaurantTable::class, 'branch_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
