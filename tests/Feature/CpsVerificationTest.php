<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VerificationLog;
use App\Models\SusResponse;
use App\Models\UsabilitySession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CpsVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected $operator;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->operator = User::factory()->create([
            'name' => 'Letnan Dua Antok',
            'email' => 'operator@poltekad.mil.id',
            'role' => 'operator_pusat',
        ]);
    }

    /**
     * Test Dashboard page renders successfully for authenticated operator.
     */
    public function test_dashboard_renders_for_operator(): void
    {
        $response = $this->actingAs($this->operator)->get('/');

        $response->assertStatus(200);
        $response->assertSee('CPS AUTHENTICATION');
        $response->assertSee('AUTENTIKASI WAJAH TERKINI');
    }

    /**
     * Test Ingestion API endpoint for embedded CPS camera.
     */
    public function test_verification_ingestion_api_creates_record(): void
    {
        $payload = [
            'subject_name' => 'Sersan Mayor Dua Taruna Arya Pratama',
            'nim' => '2024.01.0042',
            'category' => 'Taruna',
            'status' => 'verified',
            'confidence_score' => 98.5,
            'device_id' => 'CAM_GATE_UTAMA_01',
            'location' => 'Gate Utama (Pos 1 Poltekad)',
            'device_timestamp' => round(microtime(true) * 1000) - 25,
        ];

        $response = $this->postJson('/api/verifications', $payload);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'log_id',
            'status',
            'latency_ms',
        ]);

        $this->assertDatabaseHas('verification_logs', [
            'subject_name' => 'Sersan Mayor Dua Taruna Arya Pratama',
            'nim' => '2024.01.0042',
            'status' => 'verified',
        ]);
    }

    /**
     * Test SUS (System Usability Scale) Mathematical Formula & Submission.
     * Odd questions: Q - 1
     * Even questions: 5 - Q
     * All 5s: (4 * 5 + 0 * 5) * 2.5 = 50.0
     * Authentic high score answers: (Q1=5, Q2=1, Q3=5, Q4=1, Q5=5, Q6=1, Q7=5, Q8=1, Q9=5, Q10=1) -> (4*5 + 4*5)*2.5 = 100.0
     */
    public function test_sus_scoring_algorithm_accuracy(): void
    {
        $perfectAnswers = ['q1'=>5, 'q2'=>1, 'q3'=>5, 'q4'=>1, 'q5'=>5, 'q6'=>1, 'q7'=>5, 'q8'=>1, 'q9'=>5, 'q10'=>1];
        $result = SusResponse::calculateScore($perfectAnswers);

        $this->assertEquals(100.0, $result['score']);
        $this->assertEquals('A+', $result['grade']);
        $this->assertEquals('Best Imaginable', $result['adjective']);

        // Test Good SUS Score (Target > 75)
        $goodAnswers = ['q1'=>4, 'q2'=>2, 'q3'=>4, 'q4'=>1, 'q5'=>4, 'q6'=>1, 'q7'=>4, 'q8'=>2, 'q9'=>4, 'q10'=>1];
        // Odd: (3) + (3) + (3) + (3) + (3) = 15
        // Even: (3) + (4) + (4) + (3) + (4) = 18
        // Total = (15 + 18) * 2.5 = 82.5 (A, Excellent)
        $goodResult = SusResponse::calculateScore($goodAnswers);
        $this->assertEquals(82.5, $goodResult['score']);
        $this->assertEquals('A', $goodResult['grade']);

        // Submit via API
        $payload = array_merge($goodAnswers, [
            'respondent_name' => 'Letnan Dua Antok',
            'respondent_role' => 'Perwira Jaga Komando',
            'feedback' => 'UI sangat responsif dan mudah dipahami.',
        ]);

        $response = $this->postJson('/api/usability/sus/submit', $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'score' => 82.5,
            'meets_target' => true,
        ]);
    }

    /**
     * Test Usability Task Completion Time (TCT) session tracking.
     */
    public function test_usability_session_lifecycle(): void
    {
        // 1. Start Session
        $startRes = $this->postJson('/api/usability/session/start', [
            'operator_name' => 'Letnan Dua Antok',
            'task_code' => 'T1',
            'task_name' => 'Identifikasi Log Verifikasi Gagal Terkini',
        ]);

        $startRes->assertStatus(201);
        $sessionId = $startRes->json('session_id');
        $this->assertNotNull($sessionId);

        // 2. Finish Session
        $finishRes = $this->postJson('/api/usability/session/finish', [
            'session_id' => $sessionId,
            'error_count' => 0,
            'clicks_count' => 3,
            'status' => 'completed',
        ]);

        $finishRes->assertStatus(200);
        $this->assertDatabaseHas('usability_sessions', [
            'id' => $sessionId,
            'task_code' => 'T1',
            'status' => 'completed',
        ]);
    }

    /**
     * Test Manual Operator Override Action.
     */
    public function test_manual_override_action(): void
    {
        $log = VerificationLog::create([
            'subject_name' => 'Tamu Misterius',
            'status' => 'failed',
            'confidence_score' => 55.0,
            'device_id' => 'CAM_GATE_UTAMA_01',
            'location' => 'Gate Utama',
        ]);

        $response = $this->actingAs($this->operator)->postJson("/api/verifications/{$log->id}/manual-action", [
            'action' => 'approve',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('verification_logs', [
            'id' => $log->id,
            'status' => 'verified',
            'manual_override' => true,
        ]);
    }
}
