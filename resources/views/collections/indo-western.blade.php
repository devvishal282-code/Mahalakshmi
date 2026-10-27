@extends('layouts.app')

@section('title', 'Indo Western | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Indo Western',
    'description' => 'A fresh mix of contemporary styling and Indian-inspired details for your occasion wardrobe.',
    'products' => ['Modern Heirloom Set', 'Contemporary Rose Ensemble', 'Indigo Evening Edit', 'The New Classic Set'],
])
@endsection
