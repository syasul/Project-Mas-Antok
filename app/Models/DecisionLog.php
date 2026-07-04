<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DecisionLog extends Model
{
    protected $fillable = [
        'security_event_id',
        'rules_applied',
        'action_taken',
        'decision_rationale',
        'is_successful',
    ];

    protected $casts = [
        'rules_applied' => 'array',
        'action_taken' => 'array',
        'is_successful' => 'boolean',
    ];

    public function securityEvent()
    {
        return $this->belongsTo(SecurityEvent::class);
    }
}
