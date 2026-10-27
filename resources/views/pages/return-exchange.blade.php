@extends('layouts.app')

@section('title', 'Return & Exchange Policy | Mahalakshmi')

@section('content')
@include('partials.content-page-content', [
    'title' => 'Return & Exchange Policy',
    'intro' => 'Our team is happy to help with a return or exchange enquiry.',
    'sections' => [
        ['heading' => 'Start an enquiry', 'body' => 'Easy returns are offered within 15 days of purchase. Contact us as soon as possible with your order details so we can confirm eligibility and explain the next steps.'],
        ['heading' => 'Before sending an item', 'body' => 'Please wait for return or exchange instructions from our team before shipping an item back. Eligibility and available options are confirmed individually.'],
    ],
    'links' => [
        ['label' => 'Contact us on WhatsApp', 'href' => 'https://wa.me/919825572454'],
    ],
    'contactActions' => false,
    'showNewsNote' => false,
])
@endsection
