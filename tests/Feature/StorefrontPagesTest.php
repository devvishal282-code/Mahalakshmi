<?php

it('renders every collection page with the shared storefront layout', function (string $routeName, string $title) {
    $response = $this->get(route($routeName));

    $response->assertOk()
        ->assertSee($title)
        ->assertSee('festive-product-grid', false)
        ->assertSee('Inquiry via WhatsApp')
        ->assertSee('Wishlist')
        ->assertSee('site-header', false)
        ->assertSee('site-footer', false);
})->with([
    ['collections.festive-favorites', 'Festive Favourites'],
    ['collections.coord-sets', 'Co-ord Sets'],
    ['collections.designer-kurtas', 'Designer Kurtas'],
    ['collections.wedding-edit', 'Wedding Edit'],
    ['collections.autumn-edit', 'The Autumn Edit'],
    ['collections.everyday-elegance', 'Everyday Elegance'],
    ['collections.pastel-stories', 'Pastel Stories'],
    ['collections.just-added', 'Just Added'],
    ['collections.ethnic-wear', 'Ethnic Wear'],
    ['collections.cord-set', 'Cord Set'],
    ['collections.gown', 'Gown'],
    ['collections.indo-western', 'Indo Western'],
    ['collections.fusion-wear', 'Fusion Wear'],
]);

it('renders an all collections page with links to each collection', function () {
    $response = $this->get(route('collections.index'));

    $response->assertOk()
        ->assertSee('All Collections')
        ->assertSee('collections-index-grid', false)
        ->assertSee(route('collections.festive-favorites'))
        ->assertSee(route('collections.coord-sets'))
        ->assertSee(route('collections.fusion-wear'))
        ->assertSee('site-header', false)
        ->assertSee('site-footer', false);
});

it('uses the locally stored meadow video for the home hero', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('storage/videos/hero-meadow.mp4');
});

it('provides a wishlist page and links to it from the shared storefront header', function () {
    $this->get(route('wishlist.index'))
        ->assertOk()
        ->assertSee('My Wishlist')
        ->assertSee('data-wishlist-page', false)
        ->assertSee('data-wishlist-empty', false)
        ->assertSee('data-wishlist-grid', false)
        ->assertSee(route('wishlist.index'), false);
});

it('renders every information page with the shared storefront layout', function (string $routeName, string $title) {
    $response = $this->get(route($routeName));

    $response->assertOk()
        ->assertSee($title)
        ->assertSee('store-info-page', false)
        ->assertSee('site-header', false)
        ->assertSee('site-footer', false);
})->with([
    ['pages.about-us', 'About Us'],
    ['pages.contact-us', 'Contact Us'],
    ['pages.faqs', 'Frequently Asked Questions'],
    ['pages.news', 'News & Journal'],
    ['pages.privacy-policy', 'Privacy Policy'],
    ['pages.shipping-returns', 'Shipping & Returns'],
    ['pages.terms-conditions', 'Terms & Conditions'],
    ['pages.return-exchange', 'Return & Exchange Policy'],
]);

it('returns not found for collection and information pages that do not exist', function () {
    $this->get('/collections/not-a-collection')->assertNotFound();
    $this->get('/pages/not-a-page')->assertNotFound();
});

it('shows animated phone email and WhatsApp contact links', function () {
    $this->get(route('pages.contact-us'))
        ->assertOk()
        ->assertSee('contact-action--phone', false)
        ->assertSee('contact-action--email', false)
        ->assertSee('contact-action--whatsapp', false)
        ->assertSee('fa-phone', false)
        ->assertSee('fa-envelope', false)
        ->assertSee('fa-whatsapp', false)
        ->assertSee('tel:+919825572454')
        ->assertSee('tel:+919537706675')
        ->assertSee('mailto:info@mahalakshmi.com')
        ->assertSee('https://wa.me/919825572454');
});

it('links every collection and information destination from the shared layout', function () {
    $response = $this->get(route('home'));

    $response->assertOk();

    foreach ([
        'collections.coord-sets',
        'collections.designer-kurtas',
        'collections.wedding-edit',
        'collections.autumn-edit',
        'collections.everyday-elegance',
        'collections.pastel-stories',
        'collections.just-added',
        'collections.ethnic-wear',
        'collections.cord-set',
        'collections.gown',
        'collections.indo-western',
        'collections.fusion-wear',
    ] as $slug) {
        $response->assertSee(route($slug));
    }

    expect(substr_count($response->getContent(), 'href="'.route('collections.index').'"'))->toBe(4);

    foreach ([
        'pages.about-us',
        'pages.contact-us',
        'pages.faqs',
        'pages.news',
        'pages.privacy-policy',
        'pages.shipping-returns',
        'pages.terms-conditions',
        'pages.return-exchange',
    ] as $slug) {
        $response->assertSee(route($slug));
    }
});
