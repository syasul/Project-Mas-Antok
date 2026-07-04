<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityEvent extends Model
{
    protected $fillable = [
        'event_type',
        'severity',
        'status',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function decisionLogs()
    {
        return $this->hasMany(DecisionLog::class);
    }
}
