<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'rating_total',
        'rating_food',
        'rating_service',
        'rating_ambiance',
        'comment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
