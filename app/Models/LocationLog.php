<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationLog extends Model
{
    protected $fillable = [
        'tracking_session_id',
        'user_id',
        'latitude',
        'longitude',
        'logged_at',
    ];
}
