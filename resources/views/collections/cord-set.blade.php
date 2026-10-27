@extends('layouts.app')

@section('title', 'Cord Set | Mahalakshmi')

@section('content')
@include('partials.collection-page-content', [
    'title' => 'Cord Set',
    'description' => 'Discover coordinated looks that make getting dressed for any occasion feel effortless.',
    'products' => ['Summer Bloom Co-ord Set', 'Rosewood Co-ord Set', 'Ivory Garden Set', 'Printed Celebration Set'],
])
@endsection
