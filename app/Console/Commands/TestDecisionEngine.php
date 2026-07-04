<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SecurityEvent;
use App\Services\DecisionEngine;

class TestDecisionEngine extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:decision-engine';

    /**
     * The console command description.
     */
    protected $description = 'Run 50 automated test scenarios on the rule-based Decision Engine to measure accuracy';

    protected $engine;

    public function __construct(DecisionEngine $engine)
    {
        parent::__construct();
        $this->engine = $engine;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Decision Engine Accuracy Test (50 scenarios)...');

        $scenarios = [];
        
        // Populate 50 scenarios of 5 threat types (10 of each)
        for ($i = 0; $i < 10; $i++) {
            $scenarios[] = [
                'event_type' => 'armed_intruder_detected',
                'severity' => 'critical',
                'details' => ['location' => 'Sector Alpha', 'objects' => ['armed_person']],
                'expected_action' => 'engage_target_lock',
            ];
            $scenarios[] = [
                'event_type' => 'perimeter_breach_alert',
                'severity' => 'critical',
                'details' => ['zone' => 'Fence Zone 3', 'vibration' => 90],
                'expected_action' => 'pan_to_sector',
            ];
            $scenarios[] = [
                'event_type' => 'unauthorized_drone_detected',
                'severity' => 'high',
                'details' => ['location' => 'Perimeter North', 'altitude' => 120],
                'expected_action' => 'activate_rf_jamming',
            ];
            $scenarios[] = [
                'event_type' => 'iot_node_attack_suspected',
                'severity' => 'high',
                'details' => ['node_id' => 'GW_1', 'packet_loss' => 45],
                'expected_action' => 'isolate_compromised_node',
            ];
            $scenarios[] = [
                'event_type' => 'turret_offline_malfunction',
                'severity' => 'high',
                'details' => ['turret_id' => 'Turret_NW_01', 'error' => 'COM_ERR'],
                'expected_action' => 'redirect_drone_coverage',
            ];
        }

        $correctCount = 0;
        $incorrectCount = 0;
        $totalCount = count($scenarios); // exactly 50

        foreach ($scenarios as $idx => $scen) {
            // Create event
            $event = SecurityEvent::create([
                'event_type' => $scen['event_type'],
                'severity' => $scen['severity'],
                'status' => 'active',
                'details' => $scen['details'],
            ]);

            // Run engine
            $decision = $this->engine->evaluate($event);
            
            if ($decision) {
                $actions = $decision->action_taken;
                
                // Check if expected action is present in values or keys of action_taken
                $matched = false;
                foreach ($actions as $actionKey => $actionVal) {
                    if ($actionVal === $scen['expected_action'] || $actionKey === $scen['expected_action']) {
                        $matched = true;
                        break;
                    }
                }
                
                if ($matched) {
                    $correctCount++;
                } else {
                    $incorrectCount++;
                    $this->error("Scenario " . ($idx + 1) . " Failed. Expected action '" . $scen['expected_action'] . "' not found in " . json_encode($actions));
                }
            } else {
                $incorrectCount++;
                $this->error("Scenario " . ($idx + 1) . " Failed: No decision generated.");
            }
        }

        $accuracy = ($correctCount / $totalCount) * 100;
        $falseRate = ($incorrectCount / $totalCount) * 100;

        $this->info("========================================");
        $this->info("Decision Engine Testing Results:");
        $this->info("Total Scenarios Tested: $totalCount");
        $this->info("Correct Decisions: $correctCount");
        $this->info("Incorrect Decisions: $incorrectCount");
        $this->info("Accuracy Rate: " . round($accuracy, 2) . "%");
        $this->info("False Decision Rate: " . round($falseRate, 2) . "%");

        $passed = ($accuracy >= 90 && $falseRate < 10);
        if ($passed) {
            $this->info("STATUS: SUCCESS (Accuracy >= 90%, False rate < 10% targets met)");
        } else {
            $this->error("STATUS: FAILED");
        }
    }
}
