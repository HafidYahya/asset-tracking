<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAsset extends Model
{
    protected $fillable = [
        'asset_code',
        'asset_name',
        'category',
        'brand',
        'model',
        'specifications',
        'purchase_price',
        'useful_life',
        'status',
    ];
}