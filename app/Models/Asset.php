<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'asset_id', 'name', 'category', 'brand', 'model',
        'serial_number', 'condition', 'location', 'assigned_to',
        'purchase_date', 'warranty_expiry', 'purchase_price',
        'specs', 'notes',
    ];

    protected $casts = [
        'purchase_date'   => 'date',
        'warranty_expiry' => 'date',
    ];
}
