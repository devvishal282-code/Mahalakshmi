@extends('layouts.app')

@section('title', 'Designer Kurtas | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Designer Kurtas',
    'description' => 'Discover expressive prints, considered details, and versatile kurta styles to make your own.',
    'products' => ['Round Kurta Set', 'Ornate Grace Kurta Set', 'Designer A-Line Kurti Set', 'Everyday Elegance Kurta'],
])
@endsection
