@extends('layouts.app')

@section('title', 'Privacy Policy | Mahalakshmi')

@section('content')
@include('partials.content-page-content', [
    'title' => 'Privacy Policy',
    'intro' => 'We respect your privacy and want you to feel comfortable when you contact Mahalakshmi.',
    'sections' => [
        ['heading' => 'Information you choose to share', 'body' => 'If you contact us with a product or order enquiry, share only the details needed for us to respond. Please avoid sending sensitive personal or financial information through social media or messaging.'],
        ['heading' => 'Questions about your information', 'body' => 'For questions about information you have shared with us, contact our team at info@mahalakshmi.com or call +91 9825572454.'],
    ],
    'links' => [],
    'contactActions' => false,
    'showNewsNote' => false,
])
@endsection
