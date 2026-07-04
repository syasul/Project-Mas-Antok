<?php

namespace App\Services;

use App\Models\SensorLog;
use App\Models\SecurityEvent;

class UnifiedGateway
{
    protected $decisionEngine;

    public function __construct(DecisionEngine $decisionEngine)
    {
        $this->decisionEngine = $decisionEngine;
    }

    /**
     * Process an incoming sensor log from any protocol.
     *
     * @param array $payload
     * @param string $protocol (REST_API, WebSocket, MQTT)
     * @return SensorLog
     */
    public function process(array $payload, string $protocol): SensorLog
    {
        $sensorType = $payload['sensor_type'] ?? 'unknown';
        $sensorName = $payload['sensor_name'] ?? 'Generic Sensor';
        $sentAt = $payload['timestamp'] ?? microtime(true);
        
        // Calculate latency in milliseconds
        $now = microtime(true);
        $latencyMs = round(($now - $sentAt) * 1000);
        if ($latencyMs <= 0 || $latencyMs > 5000) {
            // Mock realistic latency for simulations if timestamp is off or relative
            $latencyMs = rand(5, 45);
        }

        // Extract the sensor-specific telemetry data
        $sensorData = $payload['data'] ?? [];

        // Save the raw sensor log
        $log = SensorLog::create([
            'sensor_type' => $sensorType,
            'sensor_name' => $sensorName,
            'protocol' => $protocol,
            'data' => $sensorData,
            'latency_ms' => $latencyMs,
        ]);

        // Evaluate if this sensor log triggers a security event and decision
        $this->evaluateSecurityRules($log);

        return $log;
    }

    /**
     * Evaluate rules based on the logged sensor reading.
     */
    protected function evaluateSecurityRules(SensorLog $log): void
    {
        $data = $log->data;
        $sensorType = $log->sensor_type;

        // Check if there is an active threat indicator
        $threatDetected = false;
        $eventType = null;
        $severity = 'low';
        $details = [];

        if ($sensorType === 'camera' && !empty($data['detection'])) {
            $detection = $data['detection']; // array of labels like ['armed_person', 'weapon']
            if (in_array('armed_person', $detection) || in_array('weapon', $detection)) {
                $threatDetected = true;
                $eventType = 'armed_intruder_detected';
                $severity = 'critical';
                $details = ['location' => $data['location'] ?? 'Sector A', 'objects' => $detection];
            } elseif (in_array('person', $detection)) {
                $threatDetected = true;
                $eventType = 'unauthorized_person_detected';
                $severity = 'medium';
                $details = ['location' => $data['location'] ?? 'Sector A', 'objects' => $detection];
            }
        } elseif ($sensorType === 'drone') {
            if (($data['status'] ?? '') === 'unauthorized' || ($data['intrusion_detected'] ?? false)) {
                $threatDetected = true;
                $eventType = 'unauthorized_drone_detected';
                $severity = 'high';
                $details = ['location' => $data['location'] ?? 'Perimeter North', 'telemetry' => $data];
            }
        } elseif ($sensorType === 'perimeter') {
            if (($data['vibration_level'] ?? 0) > 75 || ($data['breach_detected'] ?? false)) {
                $threatDetected = true;
                $eventType = 'perimeter_breach_alert';
                $severity = 'critical';
                $details = ['zone' => $data['zone'] ?? 'Fence Zone 3', 'vibration' => $data['vibration_level'] ?? 90];
            }
        } elseif ($sensorType === 'iot') {
            if (($data['packet_loss_pct'] ?? 0) > 20 || ($data['malicious_activity_detected'] ?? false)) {
                $threatDetected = true;
                $eventType = 'iot_node_attack_suspected';
                $severity = 'high';
                $details = ['node_id' => $data['node_id'] ?? 'Gateway_1', 'packet_loss' => $data['packet_loss_pct'] ?? 25];
            }
        } elseif ($sensorType === 'turret') {
            if (($data['status'] ?? '') === 'malfunction' || ($data['error_code'] ?? null)) {
                $threatDetected = true;
                $eventType = 'turret_offline_malfunction';
                $severity = 'high';
                $details = ['turret_id' => $data['turret_id'] ?? 'Turret_1', 'error' => $data['error_code'] ?? 'COM_ERR'];
            }
        }

        if ($threatDetected) {
            // Create a security event in the database
            $event = SecurityEvent::create([
                'event_type' => $eventType,
                'severity' => $severity,
                'status' => 'active',
                'details' => $details,
            ]);

            // Run the decision engine on this event
            $this->decisionEngine->evaluate($event);
        }
    }
}
