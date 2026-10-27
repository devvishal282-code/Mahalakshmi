<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Mahalakshmi | Contemporary Indian Wear')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>

<body>
    <div class="announcement"><span>MAHALAKSHMI • THE ART OF LIVING BEAUTIFULLY</span><span>•</span><span>Complimentary shipping across India</span><span>•</span><span>Made for every celebration</span></div>

    <header class="site-header">
        <a class="brand" href="{{ route('home') }}" aria-label="Mahalakshmi home">
            <img src="{{ asset('/images/logo_final.png') }}" alt="Mahalakshmi logo" class="logo-img">
        </a>
        <button class="menu-toggle" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
        <nav class="main-nav" aria-label="Main navigation">
            <a href="{{ route('home') }}#home">Home</a>
            <div class="nav-item">
                <a href="{{ route('home') }}#shop">Collections       <i class="fa-solid fa-chevron-down"></i></a>
                <div class="dropdown"><a href="{{ route('collections.index') }}">All Collections</a><a href="{{ route('festive-favorites') }}">Festive favourites</a><a href="{{ route('collections.coord-sets') }}">Co-ord sets</a><a href="{{ route('collections.designer-kurtas') }}">Designer kurtas</a><a href="{{ route('collections.wedding-edit') }}">Wedding edit</a></div>
            </div>
            <div class="nav-item">
                <a href="{{ route('home') }}#shop">New arrival <i class="fa-solid fa-chevron-down"></i></a>
                <div class="dropdown"><a href="{{ route('collections.autumn-edit') }}">The autumn edit</a><a href="{{ route('collections.everyday-elegance') }}">Everyday elegance</a><a href="{{ route('collections.pastel-stories') }}">Pastel stories</a><a href="{{ route('collections.just-added') }}">Just added</a></div>
            </div>
            <a href="{{ route('pages.about-us') }}">About us</a>
            <a href="{{ route('pages.contact-us') }}">Contact us</a>
        </nav>
        <div class="nav-actions">
            <a class="wish" href="{{ route('wishlist.index') }}" aria-label="Wishlist" title="Wishlist">
                <i class="fa-regular fa-heart" aria-hidden="true"></i>
                <b data-wishlist-count aria-live="polite">0</b>
                <span>Wishlist</span>
            </a>
            <button class="bag" aria-label="Cart"><i class="fa-solid fa-bag-shopping"></i><b>0</b><span>Cart</span></button>
        </div>
    </header>

    @yield('content')

    <footer class="site-footer">
        <div class="footer-main">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="footer-logo-link">
                    <img src="{{ asset('/images/logo_final.png') }}" alt="Mahalakshmi Logo">
                </a>

                <p>
                    <b>Mahalakshmi Dresses, Decor &amp; Jewellery</b> is dedicated to bringing you elegant ethnic wear, beautiful home décor, and exquisite jewellery at affordable prices, helping every woman celebrate her unique style and make every occasion special.
                </p>
            </div>

            <div class="footer-column">
                <h4>Contact us</h4>

                <h5>⌖ &nbsp; Showroom address</h5>
                <p style="font-size: 18px !important;">5,6, Mahalaxmi Imitation Jewellary, Shiv Icon, Ambaji Rd, near Yash Township, Neliyu, Rajlaxmi Society, Patan, Gujarat 384265</p>

                <a href="tel:+919825572454">♧ &nbsp; +91 9825572454</a>
                <a href="tel:+919537706675">♧ &nbsp; +91 9537706675</a>
                <a href="mailto:info@mahalakshmi.com">✉ &nbsp; info@mahalakshmi.com</a>
            </div>

            <div class="footer-column">
                <h4>Information</h4>
                <a href="{{ route('pages.about-us') }}">About us</a>
                <a href="{{ route('pages.contact-us') }}">Contact us</a>
                <a href="{{ route('pages.faqs') }}">FAQ's</a>
                <a href="{{ route('pages.news') }}">News</a>
                <a href="{{ route('pages.privacy-policy') }}">Privacy policy</a>
                <a href="{{ route('pages.shipping-returns') }}">Shipping &amp; return</a>
                <a href="{{ route('pages.terms-conditions') }}">Terms &amp; condition</a>
                <a href="{{ route('pages.return-exchange') }}">Return &amp; exchange policy</a>
            </div>

            <div class="footer-column">
                <h4>Categories</h4>
                <a href="{{ route('collections.ethnic-wear') }}">Ethnic wear</a>
                <a href="{{ route('collections.cord-set') }}">Cord set</a>
                <a href="{{ route('collections.gown') }}">Gown</a>
                <a href="{{ route('collections.indo-western') }}">Indo western</a>
                <a href="{{ route('collections.fusion-wear') }}">Fusion wear</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2026, Powered by Mohilya Couture Pvt. Ltd</p>
        </div>
    </footer>

    <button class="back-top" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>
    <script src="{{ asset('assets/js/app.js') }}" defer></script>
    @stack('scripts')
</body>

</html>
