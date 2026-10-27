@extends('layouts.app')

@section('title', 'Gown | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Gown',
    'description' => 'Explore graceful statement silhouettes for celebrations and evenings worth remembering.',
    'products' => ['Midnight Garden Gown', 'Rosewood Occasion Gown', 'Printed Grace Gown', 'Celebration Evening Gown'],
])
@endsection
