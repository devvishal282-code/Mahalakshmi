<?php

it('renders pages without a loader or scroll lock', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('data-page-loader', false)
        ->assertDontSee('page-loader-active', false);

    $this->get(route('wishlist.index'))
        ->assertOk()
        ->assertDontSee('data-page-loader', false)
        ->assertDontSee('page-loader-active', false);

    $this->get(route('admin'))
        ->assertOk()
        ->assertDontSee('data-page-loader', false);
});
