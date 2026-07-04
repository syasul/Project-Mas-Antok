<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UnifiedGateway;
use App\Models\SecurityEvent;
use Illuminate\Support\Facades\Cache;

class GatewayController extends Controller
{
    protected $gateway;

    public function __construct(UnifiedGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Unified Gateway REST API endpoint.
     */
    public function receive(Request $request)
    {
        // Check for DDoS attack simulation
        if (Cache::get('ddos_simulation_mode', false)) {
            $ip = $request->ip();
            $second = time();
            $key = 'ddos_reqs_' . $second;
            
            // Increment the counter for this exact second
            $count = Cache::get($key, 0) + 1;
            Cache::put($key, $count, 5); // keep for 5s for diagnostics

            // If requests in a single second exceed 15, trigger DDoS response and lockout
            if ($count > 15 || Cache::get('ddos_lockout', false)) {
                if (!Cache::get('ddos_lockout', false)) {
                    Cache::put('ddos_lockout', true, 30); // 30 seconds block

                    // Generate a Security Event and log it
                    $event = SecurityEvent::create([
                        'event_type' => 'ddos_attack_detected',
                        'severity' => 'critical',
                        'status' => 'active',
                        'details' => [
                            'target_endpoint' => '/api/gateway/receive',
                            'origin_ip' => $ip,
                            'peak_rate_per_sec' => $count,
                            'action' => 'Traffic blocked. Rate limiting firewall rules applied.'
                        ]
                    ]);
                }

                return response()->json([
                    'error' => 'Gateway Overloaded',
                    'message' => '503 Service Unavailable: Gateway Server Under Heavy Load (DDoS Attack)',
                    'rate_limit_exceeded' => true,
                ], 503);
            }
        }

        // Process request payloads
        $validated = $request->validate([
            'sensor_type' => 'required|string',
            'sensor_name' => 'required|string',
            'protocol' => 'nullable|string', // MQTT, WebSocket, REST_API
            'data' => 'required|array',
            'timestamp' => 'nullable|numeric',
        ]);

        $protocol = $validated['protocol'] ?? 'REST_API';
        
        try {
            $log = $this->gateway->process($validated, $protocol);
            return response()->json([
                'success' => true,
                'log_id' => $log->id,
                'latency_ms' => $log->latency_ms,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint to control DDoS Simulation Mode.
     */
    public function toggleDdos(Request $request)
    {
        $enabled = (bool) $request->input('enabled', false);
        Cache::put('ddos_simulation_mode', $enabled);
        if (!$enabled) {
            Cache::forget('ddos_lockout');
        }

        return response()->json([
            'success' => true,
            'ddos_simulation_mode' => $enabled
        ]);
    }
}
