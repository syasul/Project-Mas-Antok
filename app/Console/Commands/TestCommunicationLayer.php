<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UnifiedGateway;
use Illuminate\Support\Facades\File;

class TestCommunicationLayer extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:communication-layer';

    /**
     * The console command description.
     */
    protected $description = 'Test sending 1,000 messages through Unified Gateway and measure latency';

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
        $this->info('Starting Communication Layer Test (1,000 Messages)...');

        $protocols = ['MQTT', 'WebSocket', 'REST_API'];
        $sensorTypes = ['camera', 'drone', 'perimeter', 'iot', 'turret'];
        
        $latencies = [];
        $successCount = 0;
        
        $startTime = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            $protocol = $protocols[array_rand($protocols)];
            $sensorType = $sensorTypes[array_rand($sensorTypes)];
            
            // Build a payload with a timestamp
            $payload = [
                'sensor_type' => $sensorType,
                'sensor_name' => strtoupper($sensorType) . '_NODE_' . rand(1, 10),
                'timestamp' => microtime(true),
                'data' => [
                    'status' => 'active',
                    'value' => rand(10, 100),
                    'location' => 'Sector ' . ['Alpha', 'Beta', 'HQ'][rand(0, 2)],
                    'vibration_level' => rand(10, 50),
                    'detection' => (rand(0, 100) > 90) ? ['person'] : []
                ]
            ];

            // Measure single request process latency
            $msgStart = microtime(true);
            try {
                $this->gateway->process($payload, $protocol);
                $msgEnd = microtime(true);
                
                $singleLatencyMs = ($msgEnd - $msgStart) * 1000;
                $latencies[] = $singleLatencyMs;
                $successCount++;
            } catch (\Exception $e) {
                $this->error('Failed to process message ' . $i . ': ' . $e->getMessage());
            }
        }

        $endTime = microtime(true);
        $totalTime = ($endTime - $startTime) * 1000;
        $avgLatency = count($latencies) > 0 ? (array_sum($latencies) / count($latencies)) : 0;
        
        // Calculate P95 latency
        sort($latencies);
        $p95Index = (int) (count($latencies) * 0.95);
        $p95Latency = $latencies[$p95Index] ?? 0;

        $this->info("Test Completed!");
        $this->info("Total Sent: 1000");
        $this->info("Success: $successCount");
        $this->info("Average Latency: " . round($avgLatency, 2) . " ms");
        $this->info("P95 Latency: " . round($p95Latency, 2) . " ms");
        $this->info("Total Execution Time: " . round($totalTime, 2) . " ms");

        // Write output to txt file
        $outputDir = base_path('tests');
        if (!File::isDirectory($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        $filePath = $outputDir . '/communication_test_results.txt';
        $report = "POLTEKAD SECURITY SYSTEM - COMMUNICATION LAYER TEST REPORT\n";
        $report .= "========================================================\n";
        $report .= "Date/Time: " . now()->toDateTimeString() . "\n";
        $report .= "Total Messages Sent: 1,000\n";
        $report .= "Success Rate: " . (($successCount / 1000) * 100) . "%\n";
        $report .= "Average Latency: " . round($avgLatency, 4) . " ms\n";
        $report .= "P95 Latency: " . round($p95Latency, 4) . " ms\n";
        $report .= "Total Test Duration: " . round($totalTime, 2) . " ms\n";
        $report .= "Latencies distribution: Target average <= 200 ms\n";
        $report .= "Status: " . ($avgLatency <= 200 ? "SUCCESS (TARGET MET)" : "FAIL") . "\n";

        File::put($filePath, $report);
        $this->info("Report successfully exported to " . $filePath);
    }
}
