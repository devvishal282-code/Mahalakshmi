@extends('layouts.app')

@section('title', 'Terms & Conditions | Mahalakshmi')

@section('content')
@include('partials.content-page-content', [
    'title' => 'Terms & Conditions',
    'intro' => 'A few practical notes to help make shopping with Mahalakshmi straightforward.',
    'sections' => [
        ['heading' => 'Product enquiries and availability', 'body' => 'Product styles and availability may change. Please confirm current availability, pricing, and any order details with our team before completing a purchase.'],
        ['heading' => 'Product images and descriptions', 'body' => 'We do our best to represent each item clearly. Colours and details may appear differently depending on your screen; contact us if you need more information before ordering.'],
        ['heading' => 'Need help?', 'body' => 'For questions about an order or these terms, please contact our team using the details on our Contact Us page.'],
    ],
    'links' => [],
    'contactActions' => false,
    'showNewsNote' => false,
])
@endsection
