<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class asset extends Model
{
    protected $fillable = [
        'asset_code',
        'master_asset_id',
        'gps_number',
        'condition',
        'purchase_date',
        'purchase_price',
        'warranty_expired',
        'notes',
    ];

    public function masterAsset()
    {
        return $this->belongsTo(MasterAsset::class);
    }
}
