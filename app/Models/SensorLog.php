<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    protected $fillable = [
        'sensor_type',
        'sensor_name',
        'protocol',
        'data',
        'latency_ms',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
