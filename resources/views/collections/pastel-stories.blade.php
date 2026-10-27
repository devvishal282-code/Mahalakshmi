@extends('layouts.app')

@section('title', 'Pastel Stories | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Pastel Stories',
    'description' => 'A soft palette of delicate colours and graceful shapes for your next memorable look.',
    'products' => ['Blush Petal Kurta Set', 'Lavender Haze Ensemble', 'Ivory Garden Co-ord Set', 'Pastel Celebration Suit'],
])
@endsection
