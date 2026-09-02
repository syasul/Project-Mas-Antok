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
     * Test login page renders successfully.
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
            'rank_title' => 'Letnan Dua Antok',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}
