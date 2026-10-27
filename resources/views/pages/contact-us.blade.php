@extends('layouts.app')

@section('title', 'Contact Us | Mahalakshmi')

@section('content')
@include('partials.content-page-content', [
    'title' => 'Contact Us',
    'intro' => 'We would love to help with product enquiries, order questions, and your visit to our showroom.',
    'sections' => [
        ['heading' => 'Visit our showroom', 'body' => '5,6, Mahalaxmi Imitation Jewellary, Shiv Icon, Ambaji Rd, near Yash Township, Neliyu, Rajlaxmi Society, Patan, Gujarat 384265'],
    ],
    'links' => [
        ['label' => '+91 9825572454', 'href' => 'tel:+919825572454', 'type' => 'phone', 'icon' => 'fa-solid fa-phone'],
        ['label' => '+91 9537706675', 'href' => 'tel:+919537706675', 'type' => 'phone', 'icon' => 'fa-solid fa-phone'],
        ['label' => 'info@mahalakshmi.com', 'href' => 'mailto:info@mahalakshmi.com', 'type' => 'email', 'icon' => 'fa-solid fa-envelope'],
        ['label' => 'Message us on WhatsApp', 'href' => 'https://wa.me/919825572454', 'type' => 'whatsapp', 'icon' => 'fa-brands fa-whatsapp'],
    ],
    'contactActions' => true,
    'showNewsNote' => false,
])
@endsection
