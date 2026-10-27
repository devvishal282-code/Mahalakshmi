@extends('layouts.app')

@section('title', 'News & Journal | Mahalakshmi')

@section('content')
@include('partials.content-page-content', [
    'title' => 'News & Journal',
    'intro' => 'Style notes and inspiration from the Mahalakshmi collection.',
    'sections' => [
        ['heading' => 'How to find your festive colour story', 'body' => 'Start with the colours that make you feel your best, then build your celebration look around a silhouette you love.'],
        ['heading' => 'Pieces that earn a place in your wardrobe', 'body' => 'Versatile shapes and thoughtful details make it easy to bring your favourite pieces back for another occasion.'],
        ['heading' => 'The details that make an outfit linger', 'body' => 'From a distinctive print to an unexpected finish, the smallest details can make a look feel uniquely yours.'],
    ],
    'links' => [],
    'contactActions' => false,
    'showNewsNote' => true,
])
@endsection
