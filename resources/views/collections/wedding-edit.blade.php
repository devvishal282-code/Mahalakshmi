@extends('layouts.app')

@section('title', 'Wedding Edit | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Wedding Edit',
    'description' => 'Find an occasion-ready look for the celebrations, the ceremonies, and the moments in between.',
    'products' => ['Rosewood Wedding Ensemble', 'Ornate Grace Celebration Set', 'Designer Lehenga Set', 'The Wedding Guest Edit'],
])
@endsection
