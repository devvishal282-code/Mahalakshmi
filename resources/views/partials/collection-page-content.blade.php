<main class="festive-page">
    <p class="eyebrow collection-page-eyebrow">MAHALAKSHMI COLLECTIONS</p>
    <h1 class="festive-page-title">{{ $title }}</h1>
    <p class="collection-page-description">{{ $description }}</p>

    @php($images = [
        ['src' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85', 'alt' => 'Elegant Indian outfit in a warm festive tone'],
        ['src' => 'https://images.unsplash.com/photo-1585488434455-71d9f9322d7c?auto=format&fit=crop&w=700&q=85', 'alt' => 'Detailed Indian outfit in a rich jewel tone'],
        ['src' => 'https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=700&q=85', 'alt' => 'Contemporary Indian outfit with a graceful silhouette'],
        ['src' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=85', 'alt' => 'Printed Indian outfit for a special occasion'],
    ])
    <section class="festive-product-grid" aria-label="{{ $title }} collection">
        @foreach ($products as $index => $product)
            @php($image = $images[$index % count($images)])
            <article class="festive-product-card">
                <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}">
                <a class="festive-whatsapp" href="https://wa.me/919825572454?text={{ rawurlencode("I'm interested in the {$product}") }}">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Inquiry via WhatsApp
                </a>
                <button class="festive-wishlist heart" type="button" aria-label="Add {{ $product }} to wishlist">
                    <i class="fa-regular fa-heart" aria-hidden="true"></i> Wishlist
                </button>
                <h2>{{ $product }}</h2>
                <p class="festive-rating" aria-label="5 out of 5 stars">☆ ☆ ☆ ☆ ☆</p>
            </article>
        @endforeach
    </section>
</main>
