<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsabilitySession extends Model
{
    use HasFactory;

    protected $table = 'usability_sessions';

    protected $fillable = [
        'operator_name',
        'task_code',
        'task_name',
        'start_time',
        'end_time',
        'completion_time_sec',
        'error_count',
        'clicks_count',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'completion_time_sec' => 'float',
        'error_count' => 'integer',
        'clicks_count' => 'integer',
    ];
}
