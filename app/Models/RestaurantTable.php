<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'name',
        'capacity',
        'floor',
        'area',
        'status',
        'qr_token',
        'qr_path',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Generate and save a QR code for this table.
     */
    public function generateQrCode()
    {
        if (!$this->qr_token) {
            $this->qr_token = \Illuminate\Support\Str::random(24);
        }

        $restaurant = $this->branch->restaurant;
        $url = route('menu.index', [
            'restaurant' => $restaurant->slug,
            'table'      => $this->qr_token
        ]);

        $fileName = 'qrcodes/table_' . $this->qr_token . '.svg';
        
        // Ensure directory exists
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('qrcodes')) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('qrcodes');
        }

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(400)
            ->margin(1)
            ->generate($url);

        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $qrCode);

        $this->qr_path = $fileName;
        $this->save();

        return $fileName;
    }
}
