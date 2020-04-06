<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAdminCanSeeEmailRequiredValidationError()
    {
        // Arrange
        $credentials = [
            'email' => null,
            'password' => 'password'
        ];

        // Act
        $response = $this->post(route('admin.login'), $credentials);

        // Assert
        $response->assertSessionHasErrors('email', 'The email field is required.');
    }


    /** @test */
    public function anAdminCanSeeEmailEmailValidationError()
    {
        // Arrange
        $credentials = [
            'email' => 'abcndefg',
            'password' => 'password'
        ];

        // Act
        $response = $this->post(route('admin.login'), $credentials);

        // Assert
        $response->assertSessionHasErrors('email', 'The email must be a valid email address.');
    }

    /** @test */
    public function anAdminCanSeePasswordRequiredValidationError()
    {
        // Arrange
        $credentials = [
            'email' => 'superadmin@gmail.com',
            'password' => null
        ];

        // Act
        $response = $this->post(route('admin.login'), $credentials);

        // Assert
        $response->assertSessionHasErrors('password', 'The password field is required.');
    }


    /** @test */
    public function anAdminCanSeePasswordMinValidationError()
    {
        // Arrange
        $credentials = [
            'email' => 'superadmin@gmail.com',
            'password' => 'abcdefg'
        ];

        // Act
        $response = $this->post(route('admin.login'), $credentials);

        // Assert
        $response->assertSessionHasErrors('password', 'The password must be at least 8 characters.');
    }

    /** @test */
    public function anAdminCanSeeInvalidEmailOrPasswordError()
    {
        // Arrange
        factory(Admin::class)->create([
            'email' => 'superadmin@gmail.com',
        ]);

        $credentials = [
            'email' => 'superadmin@gmail.com',
            'password' => 'passworddd'
        ];

        // Act
        $response = $this->from('/admin/login')->post(route('admin.login'), $credentials);

        // Assert
        $response->assertSessionhas('invalidLogin', 'Invalid email or password.');
        $response->assertSessionHasInput('email', $credentials['email']);
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function anAdminCanLoginToTheAdminPanel()
    {
        // Arrange
        $admin = factory(Admin::class)->create([
            'email' => 'superadmin@gmail.com',
        ]);

        $credentials = [
            'email' => 'superadmin@gmail.com',
            'password' => 'password'
        ];

        // Act
        $response = $this->post(route('admin.login'), $credentials);

        // Assert
        $this->assertAuthenticated('admin');
        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect(route('admin.dashboard'));
    }
}
