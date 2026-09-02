<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DecisionLog;
use App\Models\SensorLog;
use App\Models\SecurityEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    /**
     * Render the official Military Incident & Decision Security Report (PDF Printable View).
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(7)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $decisions = DecisionLog::with('securityEvent')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $eventsCount = SecurityEvent::count();
        $criticalEventsCount = SecurityEvent::where('severity', 'high')->count();
        $sensorLogsCount = SensorLog::count();
        $currentUser = Auth::user();

        $reportMeta = [
            'doc_number' => 'LAP-OPS/POLTEKAD/' . date('Y/m/') . sprintf('%04d', rand(100, 999)),
            'classification' => 'RAHASIA / TACTICAL RESTRICTED',
            'generated_at' => Carbon::now()->isoFormat('D MMMM Y, HH:mm:ss') . ' WIB',
            'officer_name' => $currentUser ? $currentUser->name : 'Letnan Dua Agung Nugroho',
            'officer_rank' => $currentUser ? ($currentUser->rank_title ?? 'Perwira Jaga Taktis') : 'Perwira Jaga Taktis',
            'officer_role' => $currentUser ? $currentUser->role_label : 'Operator Pusat Komando',
            'total_incidents' => $eventsCount,
            'critical_threats' => $criticalEventsCount,
            'total_telemetry' => $sensorLogsCount,
        ];

        return view('reports.incident-pdf', compact('decisions', 'reportMeta', 'startDate', 'endDate'));
    }

    /**
     * Export Sensor Telemetry Logs as a formatted CSV file.
     */
    public function exportSensorCsv(Request $request): StreamedResponse
    {
        $fileName = 'telemetri_sensor_poltekad_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header Row
            fputcsv($handle, [
                'ID',
                'Waktu (WIB)',
                'Tipe Sensor',
                'Nama Sensor',
                'Protokol Komunikasi',
                'Latensi (ms)',
                'Detail Data Telemetri (JSON)',
            ]);

            SensorLog::orderBy('id', 'desc')->chunk(500, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-',
                        strtoupper($log->sensor_type),
                        $log->sensor_name,
                        $log->protocol,
                        $log->latency_ms,
                        json_encode($log->data, JSON_UNESCAPED_UNICODE),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Decision Logs as a formatted CSV file.
     */
    public function exportDecisionCsv(Request $request): StreamedResponse
    {
        $fileName = 'log_keputusan_keamanan_poltekad_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header Row
            fputcsv($handle, [
                'ID',
                'Waktu Kejadian',
                'Tipe Ancaman (Event)',
                'Tingkat Bahaya (Severity)',
                'Aturan IF-THEN Terpicu',
                'Tindakan Respons Sistem',
                'Justifikasi / Rationale Keputusan',
                'Status Eksekusi',
            ]);

            DecisionLog::with('securityEvent')->orderBy('id', 'desc')->chunk(500, function ($decisions) use ($handle) {
                foreach ($decisions as $d) {
                    $event = $d->securityEvent;
                    fputcsv($handle, [
                        $d->id,
                        $d->created_at ? $d->created_at->format('Y-m-d H:i:s') : '-',
                        $event ? strtoupper(str_replace('_', ' ', $event->event_type)) : 'UNIDENTIFIED EVENT',
                        $event ? strtoupper($event->severity) : 'MEDIUM',
                        is_array($d->rules_applied) ? implode(', ', $d->rules_applied) : $d->rules_applied,
                        is_array($d->action_taken) ? implode('; ', $d->action_taken) : $d->action_taken,
                        $d->decision_rationale,
                        $d->is_successful ? 'BERHASIL DIRESPON' : 'GAGAL',
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Trigger / Simulate Tactical Emergency Telegram Notification Dispatch.
     */
    public function sendTelegramAlert(Request $request)
    {
        $threatType = $request->input('threat_type', 'PERIMETER_BREACH');
        $location = $request->input('location', 'Sektor Alpha - Pos Jaga Utama');
        $operator = Auth::user() ? Auth::user()->name : 'Letnan Dua Agung Nugroho';
        $timestamp = Carbon::now()->isoFormat('D MMMM Y, HH:mm:ss') . ' WIB';

        $message = "🚨 [POLTEKAD DEFENSE ALERT] 🚨\n"
                 . "=============================\n"
                 . "⚠️ TINGKAT: CRITICAL / SIAGA 1\n"
                 . "🎯 INSIDEN: " . strtoupper(str_replace('_', ' ', $threatType)) . "\n"
                 . "📍 LOKASI: {$location}\n"
                 . "👤 PERWIRA JAGA: {$operator}\n"
                 . "⏰ WAKTU: {$timestamp}\n"
                 . "⚡ STATUS RESPON: TURRET LOCKED, DRONE DEPLOYED, REGU PATROLI BERGERAK\n"
                 . "=============================";

        // In a live system, this connects to https://api.telegram.org/bot<token>/sendMessage
        // Here we simulate the successful dispatch and return the payload
        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi taktis darurat berhasil dipancarkan ke Grup Telegram Komando Poltekad.',
            'payload' => [
                'channel' => '@PoltekadSecurityCommand',
                'content' => $message,
                'sent_at' => Carbon::now()->toIso8601String(),
                'status_code' => 200,
            ],
        ]);
    }
}
