<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_user_can_access_dashboard()
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        // Act: Attempt to access dashboard as admin
        $response = $this->actingAs($admin)
                        ->get('/dashboard');

        // Assert: Admin can access dashboard
        $response->assertOk();
        $response->assertInertia('Dashboard');
    }

    /** @test */
    public function regular_user_cannot_access_dashboard()
    {
        // Create a regular user
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        // Act: Attempt to access dashboard as regular user
        $response = $this->actingAs($user)
                        ->get('/dashboard');

        // Assert: User is redirected to homepage with error message
        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Unauthorized access.');
    }

    /** @test */
    public function guest_cannot_access_dashboard()
    {
        // Act: Attempt to access dashboard as guest (not logged in)
        $response = $this->get('/dashboard');

        // Assert: Guest is redirected to login page
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function homepage_is_accessible_to_all_users()
    {
        // Test as guest
        $response = $this->get('/');
        $response->assertOk();

        // Test as regular user
        $user = User::factory()->create(['role' => User::ROLE_USER]);
        $response = $this->actingAs($user)->get('/');
        $response->assertOk();

        // Test as admin
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $response = $this->actingAs($admin)->get('/');
        $response->assertOk();
    }
}