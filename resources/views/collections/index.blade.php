@extends('layouts.app')

@section('title', 'All Collections | Mahalakshmi')

@section('content')
@php($collections = [
    ['route' => 'collections.festive-favorites', 'title' => 'Festive Favourites', 'description' => 'Celebrate the season with occasion-ready styles, thoughtful details, and festive colour.'],
    ['route' => 'collections.coord-sets', 'title' => 'Co-ord Sets', 'description' => 'Effortless matching sets for celebrations, get-togethers, and every stylish day in between.'],
    ['route' => 'collections.designer-kurtas', 'title' => 'Designer Kurtas', 'description' => 'Discover expressive prints, considered details, and versatile kurta styles to make your own.'],
    ['route' => 'collections.wedding-edit', 'title' => 'Wedding Edit', 'description' => 'Find an occasion-ready look for the celebrations, the ceremonies, and the moments in between.'],
    ['route' => 'collections.autumn-edit', 'title' => 'The Autumn Edit', 'description' => 'Warm hues and graceful silhouettes bring a little extra character to your seasonal wardrobe.'],
    ['route' => 'collections.everyday-elegance', 'title' => 'Everyday Elegance', 'description' => 'Easy-to-wear favourites with thoughtful details, made for days that deserve a little polish.'],
    ['route' => 'collections.pastel-stories', 'title' => 'Pastel Stories', 'description' => 'A soft palette of delicate colours and graceful shapes for your next memorable look.'],
    ['route' => 'collections.just-added', 'title' => 'Just Added', 'description' => 'Meet the latest additions to the Mahalakshmi collection, selected for your next special moment.'],
    ['route' => 'collections.ethnic-wear', 'title' => 'Ethnic Wear', 'description' => 'Explore Indian-inspired styles made to bring colour, craft, and confidence to your wardrobe.'],
    ['route' => 'collections.cord-set', 'title' => 'Cord Set', 'description' => 'Discover coordinated looks that make getting dressed for any occasion feel effortless.'],
    ['route' => 'collections.gown', 'title' => 'Gown', 'description' => 'Explore graceful statement silhouettes for celebrations and evenings worth remembering.'],
    ['route' => 'collections.indo-western', 'title' => 'Indo Western', 'description' => 'A fresh mix of contemporary styling and Indian-inspired details for your occasion wardrobe.'],
    ['route' => 'collections.fusion-wear', 'title' => 'Fusion Wear', 'description' => 'Find distinctive looks that bring together easy modern shapes and expressive tradition.'],
])
@php($images = [
    ['src' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85', 'alt' => 'Elegant Indian outfit in a warm festive tone'],
    ['src' => 'https://images.unsplash.com/photo-1585488434455-71d9f9322d7c?auto=format&fit=crop&w=700&q=85', 'alt' => 'Detailed Indian outfit in a rich jewel tone'],
    ['src' => 'https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=700&q=85', 'alt' => 'Contemporary Indian outfit with a graceful silhouette'],
    ['src' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=85', 'alt' => 'Printed Indian outfit for a special occasion'],
])
<main class="collections-index">
    <p class="eyebrow collection-page-eyebrow">DISCOVER MAHALAKSHMI</p>
    <h1 class="festive-page-title">All Collections</h1>
    <p class="collection-page-description">Explore thoughtfully selected styles for every celebration and every day.</p>

    <section class="collections-index-grid" aria-label="All collections">
        @foreach ($collections as $index => $collection)
            @php($image = $images[$index % count($images)])
            <article class="collection-index-card">
                <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}">
                <div class="collection-index-card-content">
                    <h2>{{ $collection['title'] }}</h2>
                    <p>{{ $collection['description'] }}</p>
                    <a class="button filled" href="{{ route($collection['route']) }}">
                        Explore Collection <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
        @endforeach
    </section>
</main>
@endsection
