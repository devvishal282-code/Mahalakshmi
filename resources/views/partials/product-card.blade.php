@php
    $fallbackImage = asset('images/footer-bg.webp');
    $imageUrl = $product->primaryImage?->url() ?? $fallbackImage;
    $productUrl = route('products.show', $product);
    $whatsAppUrl = 'https://wa.me/919825572454?text='.rawurlencode('Hello, I would like to know more about '.$product->name.'. '.$productUrl);
@endphp

<article class="product-card" data-category-id="{{ $product->category_id }}" data-product-id="{{ $product->id }}">
    <div class="product-image">
        <a href="{{ $productUrl }}" aria-label="View {{ $product->name }}">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
        </a>
        <button class="heart" type="button" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
        <span>{{ $product->category?->name ?? 'Collection' }}</span>
    </div>
    <h3><a href="{{ $productUrl }}">{{ $product->name }}</a></h3>
    <p class="rating">₹{{ number_format((float) $product->price) }} <em>{{ $product->stock_quantity }} in stock</em></p>
    <a class="whatsapp" href="{{ $whatsAppUrl }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</a>
</article>
