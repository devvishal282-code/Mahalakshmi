@extends('layouts.app')

@section('title', 'All Collections | Mahalakshmi')

@section('content')
<main class="collections-index">
    <p class="eyebrow collection-page-eyebrow">DISCOVER MAHALAKSHMI</p>
    <h1 class="festive-page-title">All Collections</h1>
    <p class="collection-page-description">Explore thoughtfully selected styles for every celebration and every day.</p>

    <section class="collections-index-grid" aria-label="All collections">
        @forelse ($collections as $index => $collection)
            @php($image = ['src' => $collection->image_url, 'alt' => $collection->image_alt ?: $collection->title])
            <article class="collection-index-card">
                <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}">
                <div class="collection-index-card-content">
                    <h2>{{ $collection->title }}</h2>
                    <p>{{ $collection->description }}</p>
                    <a class="button filled" href="{{ route($collection->route_name) }}">
                        Explore Collection <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
        @empty
            <p>No collections are available right now.</p>
        @endforelse
    </section>
</main>
@endsection
