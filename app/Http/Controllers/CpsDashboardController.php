<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationLog;
use App\Models\SusResponse;
use App\Models\UsabilitySession;

class CpsDashboardController extends Controller
{
    /**
     * Main Real-Time Face Authentication Dashboard (Human-Centered Design).
     */
    public function index()
    {
        $latestLog = VerificationLog::orderBy('created_at', 'desc')->first();
        $recentLogs = VerificationLog::orderBy('created_at', 'desc')->limit(20)->get();

        $stats = [
            'total' => VerificationLog::count(),
            'verified' => VerificationLog::where('status', 'verified')->count(),
            'failed' => VerificationLog::where('status', 'failed')->count(),
            'pending' => VerificationLog::where('status', 'pending')->count(),
            'avg_latency' => round(VerificationLog::avg('latency_ms') ?? 24.5, 1),
            'avg_confidence' => round(VerificationLog::avg('confidence_score') ?? 96.2, 1),
        ];

        return view('cps.dashboard', compact('latestLog', 'recentLogs', 'stats'));
    }

    /**
     * Dedicated System Usability Scale (SUS) Questionnaire Page.
     */
    public function susForm()
    {
        return view('cps.sus-form');
    }

    /**
     * Usability Research Analytics & Statistical Summary Page.
     */
    public function usabilityResults()
    {
        $susResponses = SusResponse::orderBy('created_at', 'desc')->get();
        $sessions = UsabilitySession::orderBy('created_at', 'desc')->get();
        
        $avgSus = $susResponses->count() > 0 ? round($susResponses->avg('final_score'), 2) : 0;
        $avgTct = $sessions->where('status', 'completed')->count() > 0 
            ? round($sessions->where('status', 'completed')->avg('completion_time_sec'), 2) 
            : 0;

        $totalErrors = $sessions->sum('error_count');

        return view('cps.usability-results', compact('susResponses', 'sessions', 'avgSus', 'avgTct', 'totalErrors'));
    }
}
