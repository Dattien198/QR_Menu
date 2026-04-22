<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'name',
        'price',
        'is_required',
        'max_selection',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
