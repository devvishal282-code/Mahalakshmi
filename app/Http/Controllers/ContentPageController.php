<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ContentPageController extends Controller
{
    public function aboutUs(): View
    {
        return view('pages.about-us');
    }

    public function contactUs(): View
    {
        return view('pages.contact-us');
    }

    public function faqs(): View
    {
        return view('pages.faqs');
    }

    public function news(): View
    {
        return view('pages.news');
    }

    public function privacyPolicy(): View
    {
        return view('pages.privacy-policy');
    }

    public function shippingReturns(): View
    {
        return view('pages.shipping-returns');
    }

    public function termsConditions(): View
    {
        return view('pages.terms-conditions');
    }

    public function returnExchange(): View
    {
        return view('pages.return-exchange');
    }
}
