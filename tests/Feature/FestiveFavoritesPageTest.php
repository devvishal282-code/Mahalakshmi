<?php

it('renders the festive favourites page with the shared layout and products', function () {
    $response = $this->get(route('festive-favorites'));

    $response->assertOk()
        ->assertSee('Festive Favourites')
        ->assertSee('Round Kurta set')
        ->assertSee('Ornate Grace Kurta Set')
        ->assertSee('Designer A-Line Kurti Set')
        ->assertSee('Designer Lehenga Set')
        ->assertSee('site-header', false)
        ->assertSee('site-footer', false);
});
