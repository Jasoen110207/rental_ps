<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_valid_credentials_and_pin(): void
    {
        $admin = User::create([
            'name' => 'Admin Rental',
            'email' => 'admin@rental.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'pin' => Hash::make('123456'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@rental.com',
            'password' => 'password',
            'pin' => '123456',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_kasir_can_login_with_valid_credentials_and_pin(): void
    {
        $kasir = User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@rental.com',
            'role' => 'kasir',
            'password' => Hash::make('password'),
            'pin' => Hash::make('123456'),
        ]);

        $response = $this->post('/login', [
            'email' => 'kasir@rental.com',
            'password' => 'password',
            'pin' => '123456',
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $this->assertAuthenticatedAs($kasir);
    }

    public function test_user_with_null_pin_can_login_with_default_pin_and_auto_sets_pin(): void
    {
        $user = User::create([
            'name' => 'Kasir Legacy',
            'email' => 'legacy@rental.com',
            'role' => 'kasir',
            'password' => Hash::make('password'),
            'pin' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'legacy@rental.com',
            'password' => 'password',
            'pin' => '123456',
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $this->assertAuthenticatedAs($user);

        $user->refresh();
        $this->assertNotNull($user->pin);
        $this->assertTrue(Hash::check('123456', $user->pin));
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@rental.com',
            'role' => 'kasir',
            'password' => Hash::make('password'),
            'pin' => Hash::make('123456'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'kasir@rental.com',
            'password' => 'wrong-password',
            'pin' => '123456',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_fails_with_invalid_pin(): void
    {
        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@rental.com',
            'role' => 'kasir',
            'password' => Hash::make('password'),
            'pin' => Hash::make('123456'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'kasir@rental.com',
            'password' => 'password',
            'pin' => '999999',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('pin');
        $this->assertGuest();
    }
}
