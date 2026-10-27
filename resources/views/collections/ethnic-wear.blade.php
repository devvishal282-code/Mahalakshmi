@extends('layouts.app')

@section('title', 'Ethnic Wear | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Ethnic Wear',
    'description' => 'Explore Indian-inspired styles made to bring colour, craft, and confidence to your wardrobe.',
    'products' => ['Classic Printed Kurta Set', 'Embroidered Celebration Suit', 'Graceful A-Line Ensemble', 'Festive Bloom Set'],
])
@endsection
