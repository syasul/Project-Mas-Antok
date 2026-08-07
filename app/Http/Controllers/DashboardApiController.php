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

        // Evaluate health of 5 sensor types
        $sensorHealth = [];
        $sensorTypes = ['camera', 'drone', 'perimeter', 'iot', 'turret'];
        
        foreach ($sensorTypes as $type) {
            $latestLog = SensorLog::where('sensor_type', $type)->orderBy('created_at', 'desc')->first();
            
            $isProblematic = false;
            $statusText = 'NORMAL';
            $errorMessage = '';
            
            if ($latestLog) {
                $data = $latestLog->data;
                if ($type === 'camera') {
                    if (!empty($data['detection']) && (in_array('armed_person', $data['detection']) || in_array('weapon', $data['detection']) || in_array('person', $data['detection']))) {
                        $isProblematic = true;
                        $statusText = 'WARNING';
                        $errorMessage = 'Objek mencurigakan: ' . implode(', ', $data['detection']);
                    }
                } elseif ($type === 'drone') {
                    if (($data['status'] ?? '') === 'unauthorized' || ($data['intrusion_detected'] ?? false)) {
                        $isProblematic = true;
                        $statusText = 'WARNING';
                        $errorMessage = 'Intruksi udara tidak dikenal.';
                    } elseif (($data['battery_pct'] ?? 100) < 20) {
                        $isProblematic = true;
                        $statusText = 'WARNING';
                        $errorMessage = 'Baterai drone kritis: ' . ($data['battery_pct'] ?? 0) . '%';
                    }
                } elseif ($type === 'perimeter') {
                    if (($data['vibration_level'] ?? 0) > 75 || ($data['breach_detected'] ?? false)) {
                        $isProblematic = true;
                        $statusText = 'WARNING';
                        $errorMessage = 'Penyusupan pagar, getaran: ' . ($data['vibration_level'] ?? 0) . ' Hz';
                    }
                } elseif ($type === 'iot') {
                    if (($data['packet_loss_pct'] ?? 0) > 20 || ($data['malicious_activity_detected'] ?? false)) {
                        $isProblematic = true;
                        $statusText = 'WARNING';
                        $errorMessage = 'Deteksi DDoS / loss: ' . ($data['packet_loss_pct'] ?? 0) . '%';
                    }
                } elseif ($type === 'turret') {
                    if (($data['status'] ?? '') === 'malfunction' || ($data['error_code'] ?? null)) {
                        $isProblematic = true;
                        $statusText = 'FAULT';
                        $errorMessage = 'Malfungsi sistem: ' . ($data['error_code'] ?? 'COM_ERR');
                    }
                }
            }
            
            $sensorHealth[$type] = [
                'sensor_name' => $latestLog ? $latestLog->sensor_name : 'N/A',
                'status' => $isProblematic ? 'Problematic' : 'Normal',
                'status_text' => $statusText,
                'error_message' => $errorMessage,
                'updated_at' => $latestLog ? $latestLog->created_at->toIso8601String() : null
            ];
        }

        // Fetch recent error/warning logs (anomalies) from the last 50 logs
        $recentErrorLogs = [];
        $recentLogs = SensorLog::orderBy('created_at', 'desc')->limit(50)->get();
        foreach ($recentLogs as $log) {
            $data = $log->data;
            $isErr = false;
            $errDetail = '';
            
            if ($log->sensor_type === 'camera') {
                if (!empty($data['detection']) && (in_array('armed_person', $data['detection']) || in_array('weapon', $data['detection']) || in_array('person', $data['detection']))) {
                    $isErr = true;
                    $errDetail = 'Menemukan objek mencurigakan: ' . implode(', ', $data['detection']);
                }
            } elseif ($log->sensor_type === 'drone') {
                if (($data['status'] ?? '') === 'unauthorized' || ($data['intrusion_detected'] ?? false)) {
                    $isErr = true;
                    $errDetail = 'Drone tidak dikenal terdeteksi.';
                } elseif (($data['battery_pct'] ?? 100) < 20) {
                    $isErr = true;
                    $errDetail = 'Baterai drone rendah: ' . ($data['battery_pct'] ?? 0) . '%';
                }
            } elseif ($log->sensor_type === 'perimeter') {
                if (($data['vibration_level'] ?? 0) > 75 || ($data['breach_detected'] ?? false)) {
                    $isErr = true;
                    $errDetail = 'Getaran pagar melebihi batas: ' . ($data['vibration_level'] ?? 0) . ' Hz';
                }
            } elseif ($log->sensor_type === 'iot') {
                if (($data['packet_loss_pct'] ?? 0) > 20 || ($data['malicious_activity_detected'] ?? false)) {
                    $isErr = true;
                    $errDetail = 'Deteksi anomali paket / loss: ' . ($data['packet_loss_pct'] ?? 0) . '%';
                }
            } elseif ($log->sensor_type === 'turret') {
                if (($data['status'] ?? '') === 'malfunction' || ($data['error_code'] ?? null)) {
                    $isErr = true;
                    $errDetail = 'Malfungsi sistem: ' . ($data['error_code'] ?? 'COM_ERR');
                }
            }
            
            if ($isErr) {
                $recentErrorLogs[] = [
                    'id' => $log->id,
                    'sensor_type' => $log->sensor_type,
                    'sensor_name' => $log->sensor_name,
                    'message' => $errDetail,
                    'created_at' => $log->created_at->toIso8601String()
                ];
            }
        }

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
            'sensor_health' => $sensorHealth,
            'sensor_error_logs' => $recentErrorLogs,
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
