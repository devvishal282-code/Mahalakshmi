@php
    $fallbackImage = 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85';
    $imageUrl = $product->image_path && filter_var($product->image_path, FILTER_VALIDATE_URL)
        ? $product->image_path
        : ($product->image_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_path)
            : $fallbackImage);
@endphp

<article class="product-card" data-category-id="{{ $product->category_id }}">
    <div class="product-image">
        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
        <button class="heart" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
        <span>{{ $product->category?->name ?? 'Collection' }}</span>
    </div>
    <h3>{{ $product->name }}</h3>
    <p class="rating">₹{{ number_format((float) $product->price) }} <em>{{ $product->stock_quantity }} in stock</em></p>
    <button class="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enquire via WhatsApp</button>
</article>