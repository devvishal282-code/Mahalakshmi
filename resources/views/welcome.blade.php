@extends('layouts.app')

@section('title', 'Élan — Contemporary Indian Wear')

@section('content')
<main id="home">
    <section class="hero" aria-label="Festive collection">
        <video class="hero-video" autoplay muted loop playsinline preload="auto" poster="https://images.unsplash.com/photo-1581044777550-4cfa60707c03?auto=format&fit=crop&w=1800&q=85" aria-label="Woman walking in a meadow">
            <source src="{{ asset('storage/videos/hero-meadow.mp4') }}" type="video/mp4" />
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
                <video autoplay muted loop playsinline preload="metadata" poster="https://images.unsplash.com/photo-1610652492500-ded49ceeb378?auto=format&fit=crop&w=650&q=80">
                    <source src="https://cdn.coverr.co/videos/coverr-woman-wearing-a-white-dress-5197/1080p.mp4" type="video/mp4">
                </video>
                <span>Blooming ivory</span>
            </article>

            <article class="look-card video-card">
                <video autoplay muted loop playsinline preload="metadata" poster="https://images.unsplash.com/photo-1581044777550-4cfa60707c03?auto=format&fit=crop&w=650&q=80">
                    <source src="https://cdn.coverr.co/videos/coverr-woman-walking-in-a-field-469/360p.mp4" type="video/mp4">
                </video>
                <span>Soft textures</span>
            </article>

            <article class="look-card video-card">
                <video autoplay muted loop playsinline preload="metadata" poster="https://images.unsplash.com/photo-1605763240000-7e93b172d754?auto=format&fit=crop&w=650&q=80">
                    <source src="https://cdn.coverr.co/videos/coverr-woman-wearing-a-white-dress-5197/1080p.mp4" type="video/mp4">
                </video>
                <span>Modern heirloom</span>
            </article>

            <article class="look-card video-card">
                <video autoplay muted loop playsinline preload="metadata" poster="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=650&q=80">
                    <source src="https://cdn.coverr.co/videos/coverr-woman-walking-in-a-field-469/360p.mp4" type="video/mp4">
                </video>
                <span>Afterglow</span>
            </article>
            <article class="look-card video-card">
                <video autoplay muted loop playsinline preload="metadata" poster="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=650&q=80">
                    <source src="https://cdn.coverr.co/videos/coverr-woman-walking-in-a-field-469/360p.mp4" type="video/mp4">
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
            <a href="#all" class="active" aria-selected="true">All</a>
            <a href="#coord" aria-selected="false">Co-ord sets</a>
            <a href="#festive" aria-selected="false">Festive wear</a>
            <a href="#wedding" aria-selected="false">Wedding edit</a>
            <a href="#dupattas" aria-selected="false">Dupattas</a>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <div class="product-grid">
                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85" alt="Yellow festive suit">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                            <span>New</span>
                        </div>
                        <h3>Summer bloom embroidered set</h3>
                        <p class="rating">★★★★★ <em>(24)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>

                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1585488434455-71d9f9322d7c?auto=format&fit=crop&w=700&q=85" alt="Purple festive outfit">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                            <span>Festive pick</span>
                        </div>
                        <h3>Lavender haze festive set</h3>
                        <p class="rating">★★★★★ <em>(42)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>

                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=700&q=85" alt="Pink wedding outfit">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                            <span>Wedding edit</span>
                        </div>
                        <h3>Rosewood wedding ensemble</h3>
                        <p class="rating">★★★★★ <em>(16)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>

                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=700&q=85" alt="Colorful ethnic textile">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                            <span>New</span>
                        </div>
                        <h3>Emerald embroidered dupatta</h3>
                        <p class="rating">★★★★★ <em>(31)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>
                </div>
            </div>

            <div class="tab-pane fade" id="coord" role="tabpanel">
                <div class="product-grid">
                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85" alt="Yellow co-ord set">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                            <span>New</span>
                        </div>
                        <h3>Summer bloom co-ord set</h3>
                        <p class="rating">★★★★★ <em>(24)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>

                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=700&q=85" alt="Pink co-ord outfit">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                        </div>
                        <h3>Rosewood co-ord set</h3>
                        <p class="rating">★★★★★ <em>(16)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>
                </div>
            </div>

            <div class="tab-pane fade" id="festive" role="tabpanel">
                <div class="product-grid">
                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1585488434455-71d9f9322d7c?auto=format&fit=crop&w=700&q=85" alt="Purple festive outfit">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                            <span>Festive pick</span>
                        </div>
                        <h3>Lavender haze festive set</h3>
                        <p class="rating">★★★★★ <em>(42)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>

                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85" alt="Yellow festive suit">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                        </div>
                        <h3>Golden bloom festive suit</h3>
                        <p class="rating">★★★★★ <em>(24)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>
                </div>
            </div>

            <div class="tab-pane fade" id="wedding" role="tabpanel">
                <div class="product-grid">
                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=700&q=85" alt="Pink wedding outfit">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                            <span>Wedding edit</span>
                        </div>
                        <h3>Rosewood wedding ensemble</h3>
                        <p class="rating">★★★★★ <em>(16)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>

                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1585488434455-71d9f9322d7c?auto=format&fit=crop&w=700&q=85" alt="Purple wedding outfit">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                        </div>
                        <h3>Lavender wedding collection</h3>
                        <p class="rating">★★★★★ <em>(42)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>
                </div>
            </div>

            <div class="tab-pane fade" id="dupattas" role="tabpanel">
                <div class="product-grid">
                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=700&q=85" alt="Colorful ethnic textile">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                            <span>New</span>
                        </div>
                        <h3>Emerald embroidered dupatta</h3>
                        <p class="rating">★★★★★ <em>(31)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>

                    <article class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1581044777550-4cfa60707c03?auto=format&fit=crop&w=700&q=85" alt="Ivory ethnic fabric">
                            <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                        </div>
                        <h3>Ivory festive dupatta</h3>
                        <p class="rating">★★★★★ <em>(18)</em></p>
                        <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
                    </article>
                </div>
            </div>
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

    <section class="journal wrap reveal" id="journal">
        <div class="section-top">
            <div>
                <p class="eyebrow">JOURNAL</p>
                <h2>Notes from our atelier</h2>
            </div><a class="text-link" href="#journal">View all stories <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="journal-grid">
            <article><img src="https://images.unsplash.com/photo-1610189020382-668a224d9c1e?auto=format&fit=crop&w=800&q=80" alt="Festive clothing" /><small>STYLE GUIDE · OCTOBER 08, 2026</small>
                <h3>How to find your festive colour story</h3><a href="#journal">Read more</a>
            </article>
            <article><img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=800&q=80" alt="Fashion rack" /><small>THE ATELIER · OCTOBER 04, 2026</small>
                <h3>Pieces that earn a place in your wardrobe</h3><a href="#journal">Read more</a>
            </article>
            <article><img src="https://images.unsplash.com/photo-1485968579580-b6d095142e6e?auto=format&fit=crop&w=800&q=80" alt="Woman in fashionable clothing" /><small>BEHIND THE SEAMS · SEPTEMBER 29, 2026</small>
                <h3>The details that make an outfit linger</h3><a href="#journal">Read more</a>
            </article>
        </div>
    </section>
</main>
@endsection
