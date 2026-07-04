<?php

namespace App\Services;

use App\Models\SecurityEvent;
use App\Models\DecisionLog;

class DecisionEngine
{
    /**
     * Evaluate a security event using rule-based IF-THEN logic.
     *
     * @param SecurityEvent $event
     * @return DecisionLog|null
     */
    public function evaluate(SecurityEvent $event): ?DecisionLog
    {
        $rulesApplied = [];
        $actionTaken = [];
        $rationale = '';
        $success = true;

        switch ($event->event_type) {
            case 'armed_intruder_detected':
                $rulesApplied = [
                    'trigger' => 'armed_intruder_detected',
                    'condition' => 'IF camera_detects_armed_person AND severity == critical'
                ];
                $actionTaken = [
                    'siren' => 'activate',
                    'turret' => 'engage_target_lock',
                    'drone' => 'deploy_recon',
                    'patrol' => 'dispatch_squad'
                ];
                $rationale = 'Critical threat detected. Camera verified armed intruder. Automatic countermeasures initialized: siren activated, automatic turret target lock engaged, reconnaissance drone deployed, and security patrol dispatched to ' . ($event->details['location'] ?? 'Sector A') . '.';
                break;

            case 'perimeter_breach_alert':
                $rulesApplied = [
                    'trigger' => 'perimeter_breach_alert',
                    'condition' => 'IF fence_sensor_vibration_exceeds_threshold'
                ];
                $actionTaken = [
                    'turret' => 'pan_to_sector',
                    'drone' => 'deploy_to_sector',
                    'alert_lights' => 'activate_red_flash'
                ];
                $rationale = 'Perimeter line vibration sensor triggered. Automated action: perimeter security cameras and defensive turret panned to the breach zone, surveillance drone redirected, and flashing security strobe lights activated.';
                break;

            case 'unauthorized_drone_detected':
                $rulesApplied = [
                    'trigger' => 'unauthorized_drone_detected',
                    'condition' => 'IF radar_or_camera_detects_unidentified_uav'
                ];
                $actionTaken = [
                    'jamming' => 'activate_rf_jamming',
                    'turret' => 'track_airborne_target',
                    'drone' => 'intercept_flight_path'
                ];
                $rationale = 'Unidentified airborne object (UAV) entered restricted airspace. Mitigating risk: RF jamming active to disrupt drone control signals, automated turret tracking target, and defense drone sent to intercept path.';
                break;

            case 'iot_node_attack_suspected':
                $rulesApplied = [
                    'trigger' => 'iot_node_attack_suspected',
                    'condition' => 'IF gateway_packet_loss_exceeds_threshold OR anomaly_detected'
                ];
                $actionTaken = [
                    'gateway_isolation' => 'isolate_compromised_node',
                    'encryption' => 'rotate_encryption_keys',
                    'firewall' => 'block_malicious_ip'
                ];
                $rationale = 'Network communication integrity breach. Unified gateway detected suspicious traffic / massive packet drops on node. Execution: isolated the compromised node, initiated security credential key rotation, and blocked source IP.';
                break;

            case 'turret_offline_malfunction':
                $rulesApplied = [
                    'trigger' => 'turret_offline_malfunction',
                    'condition' => 'IF defensive_unit_drops_communication_or_reports_failure'
                ];
                $actionTaken = [
                    'patrol' => 'dispatch_technician_escort',
                    'drone' => 'redirect_drone_coverage'
                ];
                $rationale = 'Defensive asset (Turret) failed heartbeat check or reports malfunction. Fallback: dispatched technicians with armed escort, and repositioned perimeter drone to provide surveillance coverage over the blindspot.';
                break;

            default:
                // No matching rule
                return null;
        }

        // Store the automated decision in the log
        return DecisionLog::create([
            'security_event_id' => $event->id,
            'rules_applied' => $rulesApplied,
            'action_taken' => $actionTaken,
            'decision_rationale' => $rationale,
            'is_successful' => $success,
        ]);
    }
}
