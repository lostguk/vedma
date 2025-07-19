<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversNothing
 */
final class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_filament_panel(): void
    {
        // Arrange: create an administrator user.
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Act & Assert: acting as the admin should allow access to the Filament dashboard.
        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }
}