<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated access redirects to login.
     */
    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test login page loads successfully.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('POLTEKAD KODIKLATAD');
    }

    /**
     * Test authenticated operator can access dashboard.
     */
    public function test_authenticated_operator_can_access_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'operator@poltekad.mil.id',
            'role' => 'operator_pusat',
            'rank_title' => 'Letnan Dua Agung Nugroho',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test gateway receive endpoint.
     */
    public function test_gateway_receive_endpoint_processes_payload(): void
    {
        $payload = [
            'sensor_type' => 'perimeter',
            'sensor_name' => 'SEISMIC_ZONE_1',
            'protocol' => 'REST_API',
            'data' => ['vibration_level' => 88.5, 'breach_detected' => true, 'sector' => 'Alpha'],
            'timestamp' => round(microtime(true) * 1000),
        ];

        $response = $this->postJson('/api/gateway/receive', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'log_id', 'latency_ms']);
    }

    /**
     * Test dashboard status API.
     */
    public function test_dashboard_status_endpoint(): void
    {
        $response = $this->getJson('/api/dashboard/status');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'server_state',
            'metrics' => [
                'cpu_usage_pct',
                'ram_usage_gb',
                'disk_usage_pct',
                'total_logs',
                'total_events',
                'total_decisions',
                'avg_latency_ms',
            ],
            'sensor_distribution',
            'active_alerts',
            'recent_decisions',
            'sensor_health',
            'sensor_error_logs',
        ]);
    }
}
