<?php

it('shows an unlinked admin login form at the admin route', function () {
    $this->get(route('admin'))
        ->assertOk()
        ->assertSee('Admin Login')
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('name="_token"', false);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee(route('admin'));
});

it('requires email and password on the admin login form', function () {
    $this->from(route('admin'))
        ->post(route('admin.login'), [])
        ->assertRedirect(route('admin'))
        ->assertSessionHasErrors(['email', 'password']);
});
