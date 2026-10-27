@extends('layouts.app')

@section('title', 'Festive Favourites | Mahalakshmi')

@section('content')
<main class="festive-page">
    <h1 class="festive-page-title">Festive Favourites</h1>

    <section class="festive-product-grid" aria-label="Festive favourites">
        <article class="festive-product-card">
            <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85" alt="Round kurta set in a festive pink tone">
            <a class="festive-whatsapp" href="https://wa.me/919825572454?text=I%27m%20interested%20in%20the%20Round%20Kurta%20set">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Inquiry via WhatsApp
            </a>
            <button class="festive-wishlist heart" type="button" aria-label="Add Round Kurta set to wishlist">
                <i class="fa-regular fa-heart" aria-hidden="true"></i> Wishlist
            </button>
            <h2>Round Kurta set</h2>
            <p class="festive-rating" aria-label="5 out of 5 stars">☆ ☆ ☆ ☆ ☆</p>
        </article>

        <article class="festive-product-card">
            <img src="https://images.unsplash.com/photo-1585488434455-71d9f9322d7c?auto=format&fit=crop&w=700&q=85" alt="Ornate purple grace kurta set">
            <a class="festive-whatsapp" href="https://wa.me/919825572454?text=I%27m%20interested%20in%20the%20Ornate%20Grace%20Kurta%20Set">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Inquiry via WhatsApp
            </a>
            <button class="festive-wishlist heart" type="button" aria-label="Add Ornate Grace Kurta Set to wishlist">
                <i class="fa-regular fa-heart" aria-hidden="true"></i> Wishlist
            </button>
            <h2>Ornate Grace Kurta Set</h2>
            <p class="festive-rating" aria-label="5 out of 5 stars">☆ ☆ ☆ ☆ ☆</p>
        </article>

        <article class="festive-product-card">
            <img src="https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=700&q=85" alt="Designer A-line kurti set">
            <a class="festive-whatsapp" href="https://wa.me/919825572454?text=I%27m%20interested%20in%20the%20Designer%20A-Line%20Kurti%20Set">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Inquiry via WhatsApp
            </a>
            <button class="festive-wishlist heart" type="button" aria-label="Add Designer A-Line Kurti Set to wishlist">
                <i class="fa-regular fa-heart" aria-hidden="true"></i> Wishlist
            </button>
            <h2>Designer A-Line Kurti Set</h2>
            <p class="festive-rating" aria-label="5 out of 5 stars">☆ ☆ ☆ ☆ ☆</p>
        </article>

        <article class="festive-product-card">
            <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=85" alt="Designer lehenga set">
            <a class="festive-whatsapp" href="https://wa.me/919825572454?text=I%27m%20interested%20in%20the%20Designer%20Lehenga%20Set">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Inquiry via WhatsApp
            </a>
            <button class="festive-wishlist heart" type="button" aria-label="Add Designer Lehenga Set to wishlist">
                <i class="fa-regular fa-heart" aria-hidden="true"></i> Wishlist
            </button>
            <h2>Designer Lehenga Set</h2>
            <p class="festive-rating" aria-label="5 out of 5 stars">☆ ☆ ☆ ☆ ☆</p>
        </article>
    </section>
</main>
@endsection
