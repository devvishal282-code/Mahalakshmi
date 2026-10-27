<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an admin to log in and returns them to the admin route', function () {
    $admin = User::factory()->create([
        'password' => 'correct-password',
    ]);
    $admin->forceFill(['is_admin' => true])->save();

    $this->post(route('admin.login'), [
        'email' => $admin->email,
        'password' => 'correct-password',
    ])
        ->assertRedirect(route('admin'));

    $this->assertAuthenticatedAs($admin);

    $this->get(route('admin'))
        ->assertOk()
        ->assertSee('Welcome, '.$admin->name)
        ->assertSee('Dashboard')
        ->assertSee('Market Place')
        ->assertSee('Logout')
        ->assertSee('Storefront preview')
        ->assertSee('Collections')
        ->assertSee('Your store activity will appear here');
});

it('rejects a valid user who is not an admin', function () {
    $user = User::factory()->create([
        'password' => 'correct-password',
    ]);

    $this->from(route('admin'))
        ->post(route('admin.login'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])
        ->assertRedirect(route('admin'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('rejects invalid admin credentials', function () {
    $admin = User::factory()->create([
        'password' => 'correct-password',
    ]);
    $admin->forceFill(['is_admin' => true])->save();

    $this->from(route('admin'))
        ->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'incorrect-password',
        ])
        ->assertRedirect(route('admin'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs out a non-admin who visits the admin route while authenticated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin'))
        ->assertOk()
        ->assertSee('Admin Login');

    $this->assertGuest();
});

it('logs out an admin and returns to the login page', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->post(route('admin.logout'))
        ->assertRedirect(route('admin'));

    $this->assertGuest();

    $this->get(route('admin'))
        ->assertOk()
        ->assertSee('Admin Login');
});
