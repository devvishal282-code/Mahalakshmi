@extends('layouts.app')

@section('title', 'Co-ord Sets | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Co-ord Sets',
    'description' => 'Effortless matching sets for celebrations, get-togethers, and every stylish day in between.',
    'products' => ['Summer Bloom Co-ord Set', 'Rosewood Co-ord Set', 'Printed Daylight Co-ord Set', 'The Celebration Co-ord Set'],
])
@endsection
