<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'imei',
        'status',
        'lat',
        'lng',
        'electQuantity',
    ];
}
