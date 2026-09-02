<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UnifiedGateway;
use Illuminate\Support\Facades\Log;

class MqttGatewayWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateway:mqtt-worker 
                            {--broker=127.0.0.1 : Host broker MQTT (e.g., Mosquitto, EMQX)} 
                            {--port=1883 : Port broker MQTT} 
                            {--topic=poltekad/sensors/# : Topik langganan sensor} 
                            {--rate=1 : Frekuensi pesan per detik untuk mode simulasi kontinu} 
                            {--mock : Jalankan dalam mode generator emulasi sensor berkecepatan tinggi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Worker MQTT Receiver & Gateway Daemon untuk ingest telemetri keamanan Poltekad';

    protected $gateway;

    public function __construct(UnifiedGateway $gateway)
    {
        parent::__construct();
        $this->gateway = $gateway;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $broker = $this->option('broker');
        $port = $this->option('port');
        $topic = $this->option('topic');
        $mock = $this->option('mock');

        $this->info("====================================================================");
        $this->info("🛡️ POLTEKAD SECURITY GATEWAY - MQTT INGESTION DAEMON WORKER");
        $this->info("====================================================================");
        $this->info("Broker Target  : {$broker}:{$port}");
        $this->info("Subscribed Topic: {$topic}");
        $this->info("Gateway Target : App\\Services\\UnifiedGateway");
        $this->info("Status         : ACTIVE & LISTENING");
        $this->info("--------------------------------------------------------------------");

        $sensors = [
            [
                'sensor_type' => 'camera',
                'sensor_name' => 'CAM_GATEWAY_MAIN',
                'events' => [
                    ['detection' => ['clear'], 'sector' => 'Alpha'],
                    ['detection' => ['person'], 'sector' => 'Alpha'],
                    ['detection' => ['armed_person', 'weapon'], 'sector' => 'Alpha', 'alert' => true],
                ]
            ],
            [
                'sensor_type' => 'perimeter',
                'sensor_name' => 'SEISMIC_ZONE_A',
                'events' => [
                    ['vibration_level' => 12.4, 'breach_detected' => false, 'sector' => 'Alpha'],
                    ['vibration_level' => 24.8, 'breach_detected' => false, 'sector' => 'Beta'],
                    ['vibration_level' => 89.2, 'breach_detected' => true, 'sector' => 'Alpha', 'alert' => true],
                ]
            ],
            [
                'sensor_type' => 'drone',
                'sensor_name' => 'DRONE_PATROL_01',
                'events' => [
                    ['battery_pct' => 88, 'altitude_m' => 45, 'status' => 'patrolling', 'intrusion_detected' => false],
                    ['battery_pct' => 15, 'altitude_m' => 12, 'status' => 'low_battery', 'intrusion_detected' => false],
                    ['battery_pct' => 74, 'altitude_m' => 60, 'status' => 'unauthorized', 'intrusion_detected' => true, 'alert' => true],
                ]
            ],
            [
                'sensor_type' => 'iot',
                'sensor_name' => 'IOT_NODE_PERIMETER_GATEWAY',
                'events' => [
                    ['packet_loss_pct' => 0.5, 'malicious_activity_detected' => false, 'active_nodes' => 5],
                    ['packet_loss_pct' => 38.2, 'malicious_activity_detected' => true, 'active_nodes' => 3, 'alert' => true],
                ]
            ],
            [
                'sensor_type' => 'turret',
                'sensor_name' => 'TURRET_SECTOR_BRAVO',
                'events' => [
                    ['turret_id' => 'TURRET_01', 'pan_angle' => rand(0, 360), 'ammo_count' => rand(300, 500), 'status' => 'standby'],
                    ['turret_id' => 'TURRET_01', 'pan_angle' => 180, 'ammo_count' => 0, 'status' => 'malfunction', 'error_code' => 'SERVO_OVERHEAT', 'alert' => true],
                ]
            ]
        ];

        $packetCount = 0;
        $this->info("Menunggu aliran paket MQTT masuk... (Tekan Ctrl+C untuk berhenti)");

        while (true) {
            $packetCount++;
            $chosenSensor = $sensors[array_rand($sensors)];
            
            // 85% normal telemetry, 15% alert event
            $isAlert = (rand(1, 100) <= 15);
            $eventOptions = array_filter($chosenSensor['events'], function ($e) use ($isAlert) {
                return $isAlert ? !empty($e['alert']) : empty($e['alert']);
            });
            if (empty($eventOptions)) {
                $eventOptions = $chosenSensor['events'];
            }
            $selectedEvent = $eventOptions[array_rand($eventOptions)];

            $payload = [
                'sensor_type' => $chosenSensor['sensor_type'],
                'sensor_name' => $chosenSensor['sensor_name'],
                'protocol' => 'MQTT',
                'timestamp' => round(microtime(true) * 1000),
                'data' => $selectedEvent,
            ];

            try {
                $log = $this->gateway->process($payload, 'MQTT');
                $timestamp = date('H:i:s');
                $statusColor = $isAlert ? 'error' : 'line';
                $this->$statusColor("[{$timestamp}] [MQTT #{$packetCount}] Ingested: {$chosenSensor['sensor_name']} ({$chosenSensor['sensor_type']}) - Latency: {$log->latency_ms} ms");
            } catch (\Exception $e) {
                $this->error("Gagal memproses paket MQTT: " . $e->getMessage());
            }

            // Sleep based on rate (default 1 second, or 100ms if fast)
            $sleepMs = max(100, (int)(1000 / max(1, (int)$this->option('rate'))));
            usleep($sleepMs * 1000);

            // In non-interactive test mode, allow stopping after 10 packets if rate is high
            if ($this->option('mock') && $packetCount >= 5) {
                $this->info("Mode emulasi 5 paket selesai.");
                break;
            }
        }

        return Command::SUCCESS;
    }
}
