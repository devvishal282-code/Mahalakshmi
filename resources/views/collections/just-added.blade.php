@extends('layouts.app')

@section('title', 'Just Added | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Just Added',
    'description' => 'Meet the latest additions to the Mahalakshmi collection, selected for your next special moment.',
    'products' => ['New Season Kurta Set', 'Fresh Bloom Co-ord Set', 'Latest Designer A-Line Set', 'New Arrival Lehenga Set'],
])
@endsection
