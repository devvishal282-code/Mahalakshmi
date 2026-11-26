@extends('layouts.app')

@section('title', $collection->title.' | Mahalakshmi')

@section('content')
<main class="festive-page">
    <p class="eyebrow collection-page-eyebrow">MAHALAKSHMI COLLECTIONS</p>
    <h1 class="festive-page-title">{{ $collection->title }}</h1>
    <p class="collection-page-description">{{ $collection->description }}</p>

    <section class="products wrap" aria-label="{{ $collection->title }} products">
        <div class="product-grid">
            @forelse ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p>No products are assigned to this collection yet.</p>
            @endforelse
        </div>
    </section>
</main>
@endsection