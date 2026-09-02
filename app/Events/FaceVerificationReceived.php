<?php

namespace App\Events;

use App\Models\VerificationLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FaceVerificationReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $log;
    public $clientTimestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(VerificationLog $log, $clientTimestamp = null)
    {
        $this->log = $log;
        $this->clientTimestamp = $clientTimestamp ?? microtime(true) * 1000;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard.verifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'face.verified';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->log->id,
            'subject_name' => $this->log->subject_name,
            'nim' => $this->log->nim,
            'category' => $this->log->category,
            'photo_url' => $this->log->photo_url,
            'status' => $this->log->status,
            'confidence_score' => $this->log->confidence_score,
            'device_id' => $this->log->device_id,
            'location' => $this->log->location,
            'latency_ms' => $this->log->latency_ms,
            'failure_reason' => $this->log->failure_reason,
            'metadata' => $this->log->metadata,
            'created_at' => $this->log->created_at->toIso8601String(),
            'server_timestamp' => round(microtime(true) * 1000),
        ];
    }
}
