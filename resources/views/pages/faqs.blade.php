@extends('layouts.app')

@section('title', 'Frequently Asked Questions | Mahalakshmi')

@section('content')
@include('partials.content-page-content', [
    'title' => 'Frequently Asked Questions',
    'intro' => 'A few helpful answers for a smooth Mahalakshmi shopping experience.',
    'sections' => [
        ['heading' => 'How can I enquire about an item?', 'body' => 'Use the WhatsApp enquiry link on a product or contact our team by phone or email. We can help with product details and availability.'],
        ['heading' => 'Do you offer shipping?', 'body' => 'Complimentary shipping across India is available. Please contact us to confirm delivery availability and timing for your order.'],
        ['heading' => 'Can I return an item?', 'body' => 'Our store information lists easy returns within 15 days of purchase. Contact our team to confirm eligibility and the steps for your item.'],
        ['heading' => 'Where is the showroom?', 'body' => 'Our showroom is in Patan, Gujarat. You can find the full address and contact numbers on the Contact Us page.'],
    ],
    'links' => [],
    'contactActions' => false,
    'showNewsNote' => false,
])
@endsection
