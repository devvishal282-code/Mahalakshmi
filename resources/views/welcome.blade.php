@extends('layouts.app')

@section('title', 'Élan — Contemporary Indian Wear')

@section('content')
<main id="home">
    <section class="hero" aria-label="Festive collection">
        <video class="hero-video" muted loop playsinline preload="none" data-deferred-autoplay poster="https://images.unsplash.com/photo-1581044777550-4cfa60707c03?auto=format&fit=crop&w=1800&q=85" aria-label="Woman walking in a meadow">
            <source data-src="{{ asset('storage/videos/hero-meadow.mp4') }}" type="video/mp4" />
        </video>
        <div class="hero-copy">
            <p>MAHALAKSHMI • THE ART OF LIVING BEAUTIFULLY</p>
            <h1>Elegance in Every Detail.</h1>
            <p>Discover timeless ethnic fashion, exquisite jewellery, and beautiful décor crafted to celebrate every special moment.</p>
            <a class="button filled" href="{{ route('collections.index') }}">Shop now <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="scroll-hint"><span></span>Scroll to explore</div>
    </section>

    <section class="perks wrap" aria-label="Shopping benefits">
        <div><i class="fa-solid fa-truck-fast"></i>
            <p><strong>Free shipping</strong><small>Free shipping for all orders</small></p>
        </div>
        <div><i class="fa-solid fa-headphones"></i>
            <p><strong>Secure payment</strong><small>100% safe payment</small></p>
        </div>
        <div><i class="fa-regular fa-credit-card"></i>
            <p><strong>Easy returns</strong><small>15 days of purchase</small></p>
        </div>
        <div><i class="fa-regular fa-calendar"></i>
            <p><strong>Daily offer</strong><small>Instant discount all 10% off</small></p>
        </div>
    </section>

    <section class="looks wrap reveal" id="shop">
        <div class="section-top">
            <div>
                <p class="eyebrow">NEW STYLE EVERYDAY</p>
                <h2>Made to be remembered</h2>
            </div>
        </div>
        <div class="look-strip" id="lookStrip">
            <article class="look-card video-card">
                <video muted loop playsinline preload="none" data-deferred-autoplay poster="https://images.unsplash.com/photo-1610652492500-ded49ceeb378?auto=format&fit=crop&w=650&q=80">
                    <source data-src="https://cdn.coverr.co/videos/coverr-woman-wearing-a-white-dress-5197/1080p.mp4" type="video/mp4">
                </video>
                <span>Blooming ivory</span>
            </article>

            <article class="look-card video-card">
                <video muted loop playsinline preload="none" data-deferred-autoplay poster="https://images.unsplash.com/photo-1581044777550-4cfa60707c03?auto=format&fit=crop&w=650&q=80">
                    <source data-src="https://cdn.coverr.co/videos/coverr-woman-walking-in-a-field-469/360p.mp4" type="video/mp4">
                </video>
                <span>Soft textures</span>
            </article>

            <article class="look-card video-card">
                <video muted loop playsinline preload="none" data-deferred-autoplay poster="https://images.unsplash.com/photo-1605763240000-7e93b172d754?auto=format&fit=crop&w=650&q=80">
                    <source data-src="https://cdn.coverr.co/videos/coverr-woman-wearing-a-white-dress-5197/1080p.mp4" type="video/mp4">
                </video>
                <span>Modern heirloom</span>
            </article>

            <article class="look-card video-card">
                <video muted loop playsinline preload="none" data-deferred-autoplay poster="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=650&q=80">
                    <source data-src="https://cdn.coverr.co/videos/coverr-woman-walking-in-a-field-469/360p.mp4" type="video/mp4">
                </video>
                <span>Afterglow</span>
            </article>
            <article class="look-card video-card">
                <video muted loop playsinline preload="none" data-deferred-autoplay poster="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=650&q=80">
                    <source data-src="https://cdn.coverr.co/videos/coverr-woman-walking-in-a-field-469/360p.mp4" type="video/mp4">
                </video>
                <span>Afterglow</span>
            </article>
        </div>
    </section>

    <section class="products wrap reveal" id="products">
        <div class="section-top centered">
            <div>
                <p class="eyebrow">DISCOVER BY STYLE</p>
                <h2>Explore categories</h2>
            </div>
        </div>
        <div class="category-links" aria-label="Product categories">
            <a href="#all" class="active" aria-selected="true" role="tab">All</a>
            @foreach ($categories as $category)
                <a href="#category-{{ $category->getRouteKey() }}" aria-selected="false" role="tab">{{ $category->name }}</a>
            @endforeach
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <div class="product-grid">
                    @forelse ($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @empty
                        <p>No products are available right now.</p>
                    @endforelse
                </div>
            </div>

            @foreach ($categories as $category)
                <div class="tab-pane fade" id="category-{{ $category->getRouteKey() }}" role="tabpanel">
                <div class="product-grid">
                    @foreach ($products->where('category_id', $category->id) as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                </div>
            @endforeach
        </div>

        <a class="button outline view-all" href="{{ route('collections.index') }}" style="width: 200px;">View all products</a>
    </section>

    <section class="statement wrap reveal" id="story">
        <p class="eyebrow">THE ÉLAN WAY</p>
        <h2>Built different. Designed to lead.<br />Chosen to shine.</h2>
        <div class="statement-grid">
            <div><i class="fa-regular fa-gem"></i><strong>Unique &amp; trending designs</strong>
                <p>Original pieces for the moments you remember.</p>
            </div>
            <div><i class="fa-solid fa-wand-magic-sparkles"></i><strong>1000+ new styles every month</strong>
                <p>Fresh silhouettes, constantly evolving.</p>
            </div>
            <div><i class="fa-solid fa-heart"></i><strong>Premium designer collection</strong>
                <p>Thoughtful finishes in every thread.</p>
            </div>
            <div><i class="fa-solid fa-box-open"></i><strong>Easy shopping experience</strong>
                <p>Delivered with care, made effortless.</p>
            </div>
        </div>
    </section>

    <section class="editorial wrap reveal"><img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=1800&q=85" alt="Woman in elegant printed clothing" />
        <div>
            <p class="eyebrow">GET 30% OFF</p>
            <h2>Deal of the<br />week</h2><a class="button filled" href="{{ route('collections.index') }}">Shop now</a>
        </div>
    </section>

    <section class="customer-love wrap reveal" id="customer-love" aria-labelledby="customer-love-title">
        <div class="customer-love-heading">
            <p class="eyebrow">OUR TESTIMONIALS</p>
            <h2 id="customer-love-title">Customer Love</h2>
        </div>
        <div class="customer-love-grid">
            <article class="customer-love-card">
                <img src="{{ asset('images/customer-love-mansi.png') }}" alt="Mansi Dubey wearing a berry ethnic outfit" loading="lazy" decoding="async" />
                <div class="customer-love-copy">
                    <div class="customer-love-stars" aria-label="5 out of 5 stars">★★★★★</div>
                    <p>Mahalakshmi offers elegant ethnic wear, trendy co-ord sets, and premium indo-western styles crafted with perfect detailing. A trusted fashion brand for women who love classy and contemporary designs.</p>
                    <strong>MANSHI DUBEY</strong>
                    <span>AHMEDABAD</span>
                </div>
            </article>
            <article class="customer-love-card">
                <img src="{{ asset('images/customer-love-sarika.png') }}" alt="Sarika Singh wearing a pink embroidered kurta set" loading="lazy" decoding="async" />
                <div class="customer-love-copy">
                    <div class="customer-love-stars" aria-label="5 out of 5 stars">★★★★★</div>
                    <p>Mahalakshmi delivers high-quality gowns, kaftans, and modern festive wear with stunning craftsmanship. The perfect destination for premium women’s fashion online.</p>
                    <strong>SARIKA SINGH</strong>
                    <span>PATAN</span>
                </div>
            </article>
        </div>
    </section>
</main>
@endsection
