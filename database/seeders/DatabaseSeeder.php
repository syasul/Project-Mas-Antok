<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SensorLog;
use App\Models\SecurityEvent;
use App\Models\DecisionLog;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default admin operator user
        User::factory()->create([
            'name' => 'Letnan Dua Agung Nugroho',
            'email' => 'operator@poltekad.mil.id',
            'password' => bcrypt('poltekad123'),
        ]);

        $this->seedSensorLogs();
        $this->seedSecurityEventsAndDecisions();
    }

    protected function seedSensorLogs(): void
    {
        $protocols = ['MQTT', 'WebSocket', 'REST_API'];
        $sensors = [
            'camera' => ['CAM_GATE_MAIN', 'CAM_WEST_PERIMETER', 'CAM_HQ_ENTRY', 'CAM_HANGAR_01'],
            'drone' => ['DRONE_RECON_A', 'DRONE_PATROL_B'],
            'perimeter' => ['FENCE_SEC_ALPHA', 'FENCE_SEC_BETA', 'FENCE_GATE_3'],
            'iot' => ['IOT_GW_HQ', 'IOT_GW_NORTH', 'IOT_GW_SOUTH'],
            'turret' => ['TURRET_NORTH_WEST', 'TURRET_SOUTH_EAST'],
        ];

        $now = Carbon::now();

        // Seed 30 realistic log records spread over the last 1 hour
        for ($i = 30; $i >= 1; $i--) {
            $sensorType = array_rand($sensors);
            $sensorName = $sensors[$sensorType][array_rand($sensors[$sensorType])];
            $protocol = $protocols[array_rand($protocols)];
            
            $data = [];
            switch ($sensorType) {
                case 'camera':
                    $data = ['location' => 'Sector Alpha', 'detection' => [], 'confidence_pct' => 0.0];
                    break;
                case 'drone':
                    $data = ['battery_pct' => rand(85, 99), 'altitude_m' => 0, 'location' => 'Hangar', 'intrusion_detected' => false];
                    break;
                case 'perimeter':
                    $data = ['zone' => 'Fence Zone ' . rand(1, 3), 'vibration_level' => rand(5, 25), 'breach_detected' => false];
                    break;
                case 'iot':
                    $data = ['node_id' => 'GW_NODE_' . rand(1, 5), 'packet_loss_pct' => rand(0, 5), 'malicious_activity_detected' => false];
                    break;
                case 'turret':
                    $data = ['turret_id' => 'TURRET_01', 'status' => 'standby', 'pan_angle' => 45];
                    break;
            }

            SensorLog::create([
                'sensor_type' => $sensorType,
                'sensor_name' => $sensorName,
                'protocol' => $protocol,
                'data' => $data,
                'latency_ms' => rand(5, 35),
                'created_at' => $now->copy()->subMinutes($i * 2),
            ]);
        }
    }

    protected function seedSecurityEventsAndDecisions(): void
    {
        $now = Carbon::now();

        // Seed a resolved Perimeter Breach event from 30 minutes ago
        $event1 = SecurityEvent::create([
            'event_type' => 'perimeter_breach_alert',
            'severity' => 'critical',
            'status' => 'resolved',
            'details' => ['zone' => 'Fence Zone Alpha', 'vibration' => 88],
            'created_at' => $now->copy()->subMinutes(30),
        ]);

        DecisionLog::create([
            'security_event_id' => $event1->id,
            'rules_applied' => [
                'trigger' => 'perimeter_breach_alert',
                'condition' => 'IF fence_sensor_vibration_exceeds_threshold'
            ],
            'action_taken' => [
                'turret' => 'pan_to_sector',
                'drone' => 'deploy_to_sector',
                'alert_lights' => 'activate_red_flash'
            ],
            'decision_rationale' => 'Perimeter fence vibration triggered. Automatic response: Turret panned to Zone Alpha, patrol drone deployed, alert strobe lights activated. Incident resolved by guard squad.',
            'is_successful' => true,
            'created_at' => $now->copy()->subMinutes(30),
        ]);

        // Seed a resolved IoT network anomaly from 15 minutes ago
        $event2 = SecurityEvent::create([
            'event_type' => 'iot_node_attack_suspected',
            'severity' => 'high',
            'status' => 'resolved',
            'details' => ['node_id' => 'GW_NODE_NORTH', 'packet_loss' => 28],
            'created_at' => $now->copy()->subMinutes(15),
        ]);

        DecisionLog::create([
            'security_event_id' => $event2->id,
            'rules_applied' => [
                'trigger' => 'iot_node_attack_suspected',
                'condition' => 'IF gateway_packet_loss_exceeds_threshold'
            ],
            'action_taken' => [
                'gateway_isolation' => 'isolate_compromised_node',
                'encryption' => 'rotate_encryption_keys'
            ],
            'decision_rationale' => 'Unified gateway detected suspicious traffic on Node North. Automated response: Compromised node isolated, local security encryption keys rotated.',
            'is_successful' => true,
            'created_at' => $now->copy()->subMinutes(15),
        ]);
    }
}
