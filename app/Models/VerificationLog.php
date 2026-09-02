<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    use HasFactory;

    protected $table = 'verification_logs';

    protected $fillable = [
        'subject_name',
        'nim',
        'category',
        'photo_url',
        'status',
        'confidence_score',
        'device_id',
        'location',
        'latency_ms',
        'failure_reason',
        'metadata',
        'manual_override',
        'overridden_by',
    ];

    protected $casts = [
        'confidence_score' => 'float',
        'latency_ms' => 'float',
        'metadata' => 'array',
        'manual_override' => 'boolean',
    ];
}
