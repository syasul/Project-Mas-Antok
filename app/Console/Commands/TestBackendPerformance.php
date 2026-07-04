<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestBackendPerformance extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:backend-performance';

    /**
     * The console command description.
     */
    protected $description = 'Simulate 50+ simultaneous connections to Dashboard API and measure p95 response time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Backend Concurrency and Latency Test...');
        
        // Start a temporary local web server on port 8999 with 16 workers
        $this->info('Starting temporary PHP server on port 8999...');
        $serverCommand = "PHP_CLI_SERVER_WORKERS=16 php -S 127.0.0.1:8999 -t " . public_path() . " > /dev/null 2>&1 &";
        exec($serverCommand);
        
        // Wait for server to boot
        sleep(2);
        
        $this->info("Server started on port 8999. Launching 50 concurrent connections...");

        $url = 'http://127.0.0.1:8999/api/dashboard/status';
        
        // Curl multi setup
        $mh = curl_multi_init();
        $handles = [];
        $startTimes = [];
        
        for ($i = 0; $i < 50; $i++) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 10 seconds timeout
            
            $handles[$i] = $ch;
            $startTimes[$i] = microtime(true);
            curl_multi_add_handle($mh, $ch);
        }
        
        // Execute the handles
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);
        
        // Analyze response times
        $latencies = [];
        $successCount = 0;
        $timeoutCount = 0;
        $errorCount = 0;
        
        for ($i = 0; $i < 50; $i++) {
            $ch = $handles[$i];
            
            $info = curl_getinfo($ch);
            $responseCode = $info['http_code'];
            $totalTime = $info['total_time'] * 1000; // convert to ms
            
            if ($responseCode === 200) {
                $successCount++;
                $latencies[] = $totalTime;
            } elseif ($responseCode === 0) {
                $timeoutCount++;
            } else {
                $errorCount++;
            }
            
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        
        curl_multi_close($mh);
        
        // Terminate the temporary server on port 8999
        $this->info("Shutting down temporary PHP server on port 8999...");
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            shell_exec("FOR /F \"tokens=5\" %a in ('netstat -aon ^| findstr 8999') do taskkill /F /PID %a");
        } else {
            shell_exec("kill -9 \$(lsof -t -i :8999) 2>/dev/null");
        }

        // Calculate statistics
        sort($latencies);
        $count = count($latencies);
        
        if ($count > 0) {
            $avgLatency = array_sum($latencies) / $count;
            $p95Index = (int) ($count * 0.95);
            $p95Latency = $latencies[$p95Index] ?? $latencies[$count - 1];
        } else {
            $avgLatency = 0;
            $p95Latency = 0;
        }

        $this->info("========================================");
        $this->info("Backend Concurrency Test Results:");
        $this->info("Total Requests: 50");
        $this->info("Success: $successCount");
        $this->info("Timeouts: $timeoutCount");
        $this->info("Errors: $errorCount");
        $this->info("Average Response Time: " . round($avgLatency, 2) . " ms");
        $this->info("p95 Response Time: " . round($p95Latency, 2) . " ms");
        
        $p95TargetMet = ($p95Latency < 500 && $successCount === 50);
        
        if ($p95TargetMet) {
            $this->info("STATUS: SUCCESS (p95 < 500 ms target met, 0 timeouts)");
        } else {
            $this->error("STATUS: FAILED (p95 exceeded 500 ms or connection failures occurred)");
        }
    }
}
