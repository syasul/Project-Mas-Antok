<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorLog;
use App\Models\SecurityEvent;
use App\Models\DecisionLog;
use App\Services\UnifiedGateway;
use Illuminate\Support\Facades\Cache;

class DashboardApiController extends Controller
{
    protected $gateway;

    public function __construct(UnifiedGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Helper to apply server state simulation (down or overload).
     */
    protected function applySimulatedServerState()
    {
        $state = Cache::get('server_simulation_state', 'normal');

        if ($state === 'down') {
            abort(500, 'Internal Server Error: Database Connection Failed or Server Offline.');
        }

        if ($state === 'overload') {
            // Sleep for 600ms to simulate overload latency (> 500ms target)
            usleep(600000);
        }
    }

    /**
     * Get system status, active alerts, and database statistics.
     */
    public function status()
    {
        $this->applySimulatedServerState();

        $activeAlerts = SecurityEvent::where('status', 'active')->orderBy('created_at', 'desc')->get();
        $recentDecisions = DecisionLog::with('securityEvent')->orderBy('created_at', 'desc')->limit(10)->get();

        $sensorCounts = [
            'camera' => SensorLog::where('sensor_type', 'camera')->count(),
            'drone' => SensorLog::where('sensor_type', 'drone')->count(),
            'perimeter' => SensorLog::where('sensor_type', 'perimeter')->count(),
            'iot' => SensorLog::where('sensor_type', 'iot')->count(),
            'turret' => SensorLog::where('sensor_type', 'turret')->count(),
        ];

        // Retrieve mock stats
        $systemState = Cache::get('server_simulation_state', 'normal');
        $ddosActive = Cache::get('ddos_simulation_mode', false);
        $ddosLockout = Cache::get('ddos_lockout', false);

        return response()->json([
            'status' => 'online',
            'server_state' => $systemState,
            'ddos_simulation_mode' => $ddosActive,
            'ddos_lockout' => $ddosLockout,
            'metrics' => [
                'cpu_usage_pct' => $systemState === 'overload' ? rand(88, 98) : rand(12, 35),
                'ram_usage_gb' => $systemState === 'overload' ? 14.8 : 4.2,
                'disk_usage_pct' => 45,
                'total_logs' => SensorLog::count(),
                'total_events' => SecurityEvent::count(),
                'total_decisions' => DecisionLog::count(),
                'avg_latency_ms' => round(SensorLog::avg('latency_ms') ?? 15, 2),
            ],
            'sensor_distribution' => $sensorCounts,
            'active_alerts' => $activeAlerts,
            'recent_decisions' => $recentDecisions,
        ]);
    }

    /**
     * Change mock server state (normal, overload, down).
     */
    public function toggleServerState(Request $request)
    {
        $state = $request->input('state', 'normal');
        if (in_array($state, ['normal', 'overload', 'down'])) {
            Cache::put('server_simulation_state', $state);
        }

        return response()->json([
            'success' => true,
            'server_state' => $state
        ]);
    }

    /**
     * Get recent sensor logs.
     */
    public function logs()
    {
        $this->applySimulatedServerState();

        $logs = SensorLog::orderBy('created_at', 'desc')->limit(50)->get();
        return response()->json($logs);
    }

    /**
     * Get recent decision logs.
     */
    public function decisions()
    {
        $this->applySimulatedServerState();

        $decisions = DecisionLog::with('securityEvent')->orderBy('created_at', 'desc')->limit(50)->get();
        return response()->json($decisions);
    }

    /**
     * Trigger a mock event via POST (used by frontend control panel).
     */
    public function triggerMockEvent(Request $request)
    {
        $this->applySimulatedServerState();

        $type = $request->input('type'); // e.g., 'breach', 'intruder', 'uav', 'iot_attack', 'turret_fail'
        $sector = $request->input('sector', 'Sector A');

        $payload = [];
        switch ($type) {
            case 'intruder':
                $payload = [
                    'sensor_type' => 'camera',
                    'sensor_name' => 'CAM_GATE_MAIN',
                    'timestamp' => microtime(true),
                    'data' => [
                        'location' => $sector,
                        'detection' => ['armed_person', 'weapon'],
                        'confidence_pct' => 98.4
                    ]
                ];
                break;
            case 'breach':
                $payload = [
                    'sensor_type' => 'perimeter',
                    'sensor_name' => 'FENCE_SEC_' . str_replace('Sector ', '', $sector),
                    'timestamp' => microtime(true),
                    'data' => [
                        'zone' => 'Fence Zone ' . str_replace('Sector ', '', $sector),
                        'vibration_level' => rand(82, 98),
                        'breach_detected' => true
                    ]
                ];
                break;
            case 'uav':
                $payload = [
                    'sensor_type' => 'drone',
                    'sensor_name' => 'DRONE_RADAR_1',
                    'timestamp' => microtime(true),
                    'data' => [
                        'location' => $sector,
                        'status' => 'unauthorized',
                        'altitude_m' => 120,
                        'intrusion_detected' => true
                    ]
                ];
                break;
            case 'iot_attack':
                $payload = [
                    'sensor_type' => 'iot',
                    'sensor_name' => 'IOT_GATEWAY_HQ',
                    'timestamp' => microtime(true),
                    'data' => [
                        'node_id' => 'GW_HQ_NODE_4',
                        'packet_loss_pct' => rand(28, 45),
                        'malicious_activity_detected' => true
                    ]
                ];
                break;
            case 'turret_fail':
                $payload = [
                    'sensor_type' => 'turret',
                    'sensor_name' => 'TURRET_NORTH_WEST',
                    'timestamp' => microtime(true),
                    'data' => [
                        'turret_id' => 'TURRET_NW_01',
                        'status' => 'malfunction',
                        'error_code' => 'FEEDBACK_LOOP_ERR'
                    ]
                ];
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid mock event type'], 400);
        }

        // Process this payload through the Unified Gateway, which triggers rules automatically!
        $log = $this->gateway->process($payload, 'REST_API');

        return response()->json([
            'success' => true,
            'message' => 'Mock event triggered successfully',
            'log_id' => $log->id
        ]);
    }
}
