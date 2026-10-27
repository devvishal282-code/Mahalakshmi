@extends('layouts.app')

@section('title', 'About Us | Mahalakshmi')

@section('content')
@include('partials.content-page-content', [
    'title' => 'About Us',
    'intro' => 'Mahalakshmi Dresses, Decor & Jewellery brings together elegant ethnic wear, beautiful home décor, and exquisite jewellery.',
    'sections' => [
        ['heading' => 'Made for every celebration', 'body' => 'We believe personal style should feel joyful and individual. Our collections bring together expressive designs and versatile favourites for the moments that matter to you.'],
        ['heading' => 'A personal shopping experience', 'body' => 'From discovering a new look to choosing something special, our team is here to help. Get in touch with us for product details and availability.'],
    ],
    'links' => [],
    'contactActions' => false,
    'showNewsNote' => false,
])
@endsection
