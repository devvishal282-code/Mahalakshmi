@extends('layouts.app')

@section('title', $product->name.' | Mahalakshmi')

@section('content')
@php
    $fallbackImage = asset('images/footer-bg.webp');
    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $mainImageUrl = $primaryImage?->url() ?? $fallbackImage;
    $whatsAppUrl = 'https://wa.me/919825572454?text='.rawurlencode('Hello, I would like to know more about '.$product->name.'. '.route('products.show', $product));
@endphp

<main class="product-detail-page">
    <nav class="product-detail-breadcrumb wrap" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span aria-hidden="true">/</span>
        @if ($product->category)
            <a href="{{ route('home') }}#products">{{ $product->category->name }}</a>
            <span aria-hidden="true">/</span>
        @endif
        <span>{{ $product->name }}</span>
    </nav>

    <section class="product-detail wrap" aria-labelledby="product-title">
        <div class="product-detail-gallery" data-product-gallery>
            <div class="product-detail-main-image">
                <img src="{{ $mainImageUrl }}" alt="{{ $product->name }}" data-product-main-image>
                @if ($product->images->count() > 1)
                    <button type="button" class="product-gallery-arrow product-gallery-arrow--previous" data-gallery-previous aria-label="Previous product image"><i class="fa-solid fa-chevron-left"></i></button>
                    <button type="button" class="product-gallery-arrow product-gallery-arrow--next" data-gallery-next aria-label="Next product image"><i class="fa-solid fa-chevron-right"></i></button>
                @endif
            </div>

            @if ($product->images->isNotEmpty())
                <div class="product-detail-thumbnails" aria-label="Product images">
                    @foreach ($product->images as $image)
                        <button type="button" class="product-detail-thumbnail{{ $image->id === $primaryImage?->id ? ' is-active' : '' }}" data-gallery-thumbnail data-image-src="{{ $image->url() }}" data-image-alt="{{ $product->name }} image {{ $loop->iteration }}" aria-label="Show image {{ $loop->iteration }}" aria-pressed="{{ $image->id === $primaryImage?->id ? 'true' : 'false' }}">
                            <img src="{{ $image->url() }}" alt="">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="product-detail-summary" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-image="{{ $mainImageUrl }}">
            <p class="product-detail-kicker">{{ $product->collections->first()?->title ?? 'Mahalakshmi Collection' }}</p>
            <h1 id="product-title">{{ $product->name }}</h1>
            <div class="product-detail-meta">
                <span class="product-detail-stars" aria-label="Five star product"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span>
                <span>{{ $product->stock_quantity > 0 ? $product->stock_quantity.' in stock' : 'Out of stock' }}</span>
            </div>
            <p class="product-detail-price">₹{{ number_format((float) $product->price, 2) }}</p>
            <p class="product-detail-description">{{ $product->description ?: 'A thoughtfully selected Mahalakshmi piece, made to bring elegance to every occasion.' }}</p>

            <dl class="product-detail-facts">
                <div><dt>Category</dt><dd>{{ $product->category?->name ?? 'Uncategorized' }}</dd></div>
                <div><dt>Availability</dt><dd>{{ $product->stock_quantity > 0 ? 'Ready to enquire' : 'Currently unavailable' }}</dd></div>
                @if ($product->collections->isNotEmpty())
                    <div><dt>Collections</dt><dd>{{ $product->collections->pluck('title')->implode(', ') }}</dd></div>
                @endif
            </dl>

            <div class="product-detail-actions">
                <a class="product-detail-whatsapp" href="{{ $whatsAppUrl }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Enquire on WhatsApp</a>
                <button class="product-detail-wishlist heart" type="button" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i><span>Add to wishlist</span></button>
            </div>

            <div class="product-detail-accordions">
                <details open>
                    <summary>Description</summary>
                    <p>{{ $product->description ?: 'Contact us for complete fabric, fit, and styling details.' }}</p>
                </details>
                <details>
                    <summary>Product information</summary>
                    <p>Product code: ML-{{ str_pad((string) $product->id, 5, '0', STR_PAD_LEFT) }}. Category: {{ $product->category?->name ?? 'Mahalakshmi collection' }}.</p>
                </details>
                <details>
                    <summary>Returns and exchanges</summary>
                    <p>Easy exchange support is available. Please review our return and exchange policy before purchase.</p>
                </details>
            </div>

            <div class="product-detail-services" aria-label="Shopping services">
                <div><i class="fa-solid fa-truck-fast"></i><span><strong>Complimentary shipping</strong><small>Across India</small></span></div>
                <div><i class="fa-solid fa-headset"></i><span><strong>Personal assistance</strong><small>Call or WhatsApp us</small></span></div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="product-related wrap" aria-labelledby="related-products-title">
            <p class="eyebrow">YOU MAY ALSO LOVE</p>
            <h2 id="related-products-title">Related products</h2>
            <div class="product-grid">
                @foreach ($relatedProducts as $relatedProduct)
                    @include('partials.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </section>
    @endif
</main>
@endsection
