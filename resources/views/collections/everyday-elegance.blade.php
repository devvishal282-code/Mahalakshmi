@extends('layouts.app')

@section('title', 'Everyday Elegance | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Everyday Elegance',
    'description' => 'Easy-to-wear favourites with thoughtful details, made for days that deserve a little polish.',
    'products' => ['Everyday A-Line Kurti', 'Soft Bloom Co-ord Set', 'Printed Grace Kurta', 'The Easy Elegance Set'],
])
@endsection
