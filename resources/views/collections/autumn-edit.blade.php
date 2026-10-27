@extends('layouts.app')

@section('title', 'The Autumn Edit | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'The Autumn Edit',
    'description' => 'Warm hues and graceful silhouettes bring a little extra character to your seasonal wardrobe.',
    'products' => ['Autumn Bloom Suit', 'Golden Hour Kurta Set', 'Harvest Rose Ensemble', 'Evening Ember Co-ord Set'],
])
@endsection
