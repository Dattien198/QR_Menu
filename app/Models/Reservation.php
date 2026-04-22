<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'phone',
        'email',
        'people_count',
        'reservation_at',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
