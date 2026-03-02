<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracksolidToken extends Model
{
    protected $fillable = [
        'access_token',
        'refresh_token',
        'expired_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime'
    ];
}
