@extends('layouts.app')

@section('title', 'Wishlist | Mahalakshmi')

@section('content')
<main class="festive-page wishlist-page" data-wishlist-page>
    <p class="eyebrow collection-page-eyebrow">YOUR SAVED FAVOURITES</p>
    <h1 class="festive-page-title">My Wishlist</h1>
    <p class="collection-page-description">Keep the styles you love close at hand.</p>

    <p class="wishlist-empty" data-wishlist-empty hidden>
        Your wishlist is empty. Browse our collections and tap the heart on anything you love.
        <a class="button filled" href="{{ route('collections.index') }}">Explore collections</a>
    </p>

    <section class="festive-product-grid wishlist-grid" aria-label="Wishlist products" data-wishlist-grid></section>
</main>
@endsection
