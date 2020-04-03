<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAdminCanLoginToTheAdminPanel()
    {
        // Arrange
        $admin = [
            'username' => 'superadmin@gmail.com',
            'password' => 'password'
        ];

        // Act
        $response = $this->post(route('admin.login'), $admin);

        // Assert
        $response->assertStatus(200);
        $this->assertAuthenticated('admin');
        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSee('Dashboard');
    }
}
