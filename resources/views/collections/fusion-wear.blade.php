@extends('layouts.app')

@section('title', 'Fusion Wear | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Fusion Wear',
    'description' => 'Find distinctive looks that bring together easy modern shapes and expressive tradition.',
    'products' => ['Afterglow Fusion Set', 'Printed Modern Kurta', 'Soft Texture Co-ord', 'Celebration Fusion Ensemble'],
])
@endsection
