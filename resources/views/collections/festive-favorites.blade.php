@extends('layouts.app')

@section('title', 'Festive Favourites | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Festive Favourites',
    'description' => 'Celebrate the season with occasion-ready styles, thoughtful details, and festive colour.',
    'products' => ['Round Kurta Set', 'Ornate Grace Kurta Set', 'Designer A-Line Kurti Set', 'Designer Lehenga Set'],
])
@endsection
