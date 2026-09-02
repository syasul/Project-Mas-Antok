<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsabilitySession;
use App\Models\SusResponse;
use App\Models\VerificationLog;

class UsabilityController extends Controller
{
    /**
     * Start a new Usability Task Completion Time (TCT) session.
     * POST /api/usability/session/start
     */
    public function startSession(Request $request)
    {
        $validated = $request->validate([
            'operator_name' => 'required|string|max:100',
            'task_code' => 'required|string|max:10',
            'task_name' => 'required|string|max:200',
        ]);

        $session = UsabilitySession::create([
            'operator_name' => $validated['operator_name'],
            'task_code' => $validated['task_code'],
            'task_name' => $validated['task_name'],
            'start_time' => now(),
            'status' => 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'start_time' => $session->start_time->toIso8601String(),
            'message' => 'Sesi pengujian usability dimulai.'
        ], 201);
    }

    /**
     * Finish a Usability Task session and record completion time & errors.
     * POST /api/usability/session/finish
     */
    public function finishSession(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:usability_sessions,id',
            'error_count' => 'nullable|integer|min:0',
            'clicks_count' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:completed,abandoned',
            'notes' => 'nullable|string',
        ]);

        $session = UsabilitySession::findOrFail($validated['session_id']);
        $endTime = now();
        $startTime = $session->start_time ?? $endTime;
        
        $durationSec = max(0.5, round($endTime->diffInMilliseconds($startTime) / 1000, 2));

        $session->update([
            'end_time' => $endTime,
            'completion_time_sec' => $durationSec,
            'error_count' => $validated['error_count'] ?? 0,
            'clicks_count' => $validated['clicks_count'] ?? 0,
            'status' => $validated['status'] ?? 'completed',
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'session' => $session,
            'completion_time_sec' => $durationSec,
            'message' => 'Sesi tugas berhasil dicatat: ' . $durationSec . ' detik.'
        ]);
    }

    /**
     * Submit SUS (System Usability Scale) Questionnaire response.
     * POST /api/usability/sus/submit
     */
    public function submitSus(Request $request)
    {
        $validated = $request->validate([
            'respondent_name' => 'required|string|max:150',
            'respondent_role' => 'nullable|string|max:100',
            'q1' => 'required|integer|min:1|max:5',
            'q2' => 'required|integer|min:1|max:5',
            'q3' => 'required|integer|min:1|max:5',
            'q4' => 'required|integer|min:1|max:5',
            'q5' => 'required|integer|min:1|max:5',
            'q6' => 'required|integer|min:1|max:5',
            'q7' => 'required|integer|min:1|max:5',
            'q8' => 'required|integer|min:1|max:5',
            'q9' => 'required|integer|min:1|max:5',
            'q10' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Calculate score using standard John Brooke SUS formula
        $calc = SusResponse::calculateScore($validated);

        $sus = SusResponse::create([
            'respondent_name' => $validated['respondent_name'],
            'respondent_role' => $validated['respondent_role'] ?? 'Operator Lapangan',
            'q1' => $validated['q1'],
            'q2' => $validated['q2'],
            'q3' => $validated['q3'],
            'q4' => $validated['q4'],
            'q5' => $validated['q5'],
            'q6' => $validated['q6'],
            'q7' => $validated['q7'],
            'q8' => $validated['q8'],
            'q9' => $validated['q9'],
            'q10' => $validated['q10'],
            'final_score' => $calc['score'],
            'grade' => $calc['grade'],
            'adjective_rating' => $calc['adjective'],
            'feedback' => $validated['feedback'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'sus_id' => $sus->id,
            'score' => $sus->final_score,
            'grade' => $sus->grade,
            'adjective' => $sus->adjective_rating,
            'meets_target' => $sus->final_score >= 75.0, // Indikator target > 75
            'message' => "Kuesioner SUS berhasil disimpan. Skor: {$sus->final_score} ({$sus->adjective_rating})",
            'data' => $sus,
        ], 201);
    }

    /**
     * Get aggregate usability statistics for research dashboard & thesis analysis.
     * GET /api/usability/stats
     */
    public function getStats()
    {
        $susResponses = SusResponse::orderBy('created_at', 'desc')->get();
        $totalRespondents = $susResponses->count();
        $avgSusScore = $totalRespondents > 0 ? round($susResponses->avg('final_score'), 2) : 0;

        // Determine average grade and adjective
        $overallGrade = 'F';
        $overallAdjective = 'Poor';
        if ($avgSusScore >= 85.0) { $overallGrade = 'A+'; $overallAdjective = 'Best Imaginable'; }
        elseif ($avgSusScore >= 80.3) { $overallGrade = 'A'; $overallAdjective = 'Excellent'; }
        elseif ($avgSusScore >= 74.0) { $overallGrade = 'B'; $overallAdjective = 'Good (Acceptable)'; }
        elseif ($avgSusScore >= 68.0) { $overallGrade = 'C'; $overallAdjective = 'OK'; }

        // Usability Task Sessions
        $completedSessions = UsabilitySession::where('status', 'completed')->get();
        $avgTctSec = $completedSessions->count() > 0 ? round($completedSessions->avg('completion_time_sec'), 2) : 0;
        $totalErrors = $completedSessions->sum('error_count');
        $errorRatePct = $completedSessions->count() > 0 ? round(($totalErrors / $completedSessions->count()) * 100, 1) : 0;

        // Tasks breakdown
        $taskStats = [];
        $tasks = ['T1', 'T2', 'T3', 'T4'];
        foreach ($tasks as $code) {
            $taskGroup = $completedSessions->where('task_code', $code);
            if ($taskGroup->isNotEmpty()) {
                $taskStats[$code] = [
                    'task_name' => $taskGroup->first()->task_name,
                    'count' => $taskGroup->count(),
                    'avg_tct_sec' => round($taskGroup->avg('completion_time_sec'), 2),
                    'total_errors' => $taskGroup->sum('error_count'),
                ];
            }
        }

        // WebSocket End-to-End Latency distribution from verification logs
        $logs = VerificationLog::all();
        $avgLatency = $logs->count() > 0 ? round($logs->avg('latency_ms'), 2) : 0;
        $sub100Count = $logs->where('latency_ms', '<', 100)->count();
        $sub100Pct = $logs->count() > 0 ? round(($sub100Count / $logs->count()) * 100, 1) : 100;

        return response()->json([
            'sus_summary' => [
                'total_respondents' => $totalRespondents,
                'avg_score' => $avgSusScore,
                'grade' => $overallGrade,
                'adjective_rating' => $overallAdjective,
                'target_met' => $avgSusScore >= 75.0,
                'min_score' => $susResponses->min('final_score') ?? 0,
                'max_score' => $susResponses->max('final_score') ?? 0,
            ],
            'tct_summary' => [
                'total_tested_sessions' => $completedSessions->count(),
                'avg_completion_time_sec' => $avgTctSec,
                'total_errors' => $totalErrors,
                'error_rate_pct' => $errorRatePct,
                'tasks_breakdown' => $taskStats,
            ],
            'websocket_latency_summary' => [
                'avg_latency_ms' => $avgLatency,
                'sub_100ms_compliance_pct' => $sub100Pct,
                'target_met' => $avgLatency < 100.0,
            ],
            'all_sus_responses' => $susResponses,
            'recent_sessions' => UsabilitySession::orderBy('created_at', 'desc')->limit(15)->get(),
        ]);
    }
}
