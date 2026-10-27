@extends('layouts.app')

@section('title', 'Shipping & Returns | Mahalakshmi')

@section('content')
@include('partials.content-page-content', [
    'title' => 'Shipping & Returns',
    'intro' => 'We want your Mahalakshmi order to reach you with care. Contact our team for help with delivery and return enquiries.',
    'sections' => [
        ['heading' => 'Shipping', 'body' => 'Complimentary shipping across India is available. Delivery availability and estimated timing can vary by destination; please confirm details with our team when placing your order.'],
        ['heading' => 'Returns', 'body' => 'Easy returns are offered within 15 days of purchase. Please contact our team to confirm whether your item is eligible and to receive return instructions.'],
    ],
    'links' => [
        ['label' => 'Ask us about your order', 'href' => 'https://wa.me/919825572454'],
    ],
    'contactActions' => false,
    'showNewsNote' => false,
])
@endsection
