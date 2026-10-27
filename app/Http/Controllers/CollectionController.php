<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        return view('collections.index');
    }

    public function festiveFavorites(): View
    {
        return view('festive-favorites');
    }

    public function festiveFavoritesCollection(): View
    {
        return view('collections.festive-favorites');
    }

    public function coordSets(): View
    {
        return view('collections.coord-sets');
    }

    public function designerKurtas(): View
    {
        return view('collections.designer-kurtas');
    }

    public function weddingEdit(): View
    {
        return view('collections.wedding-edit');
    }

    public function autumnEdit(): View
    {
        return view('collections.autumn-edit');
    }

    public function everydayElegance(): View
    {
        return view('collections.everyday-elegance');
    }

    public function pastelStories(): View
    {
        return view('collections.pastel-stories');
    }

    public function justAdded(): View
    {
        return view('collections.just-added');
    }

    public function ethnicWear(): View
    {
        return view('collections.ethnic-wear');
    }

    public function cordSet(): View
    {
        return view('collections.cord-set');
    }

    public function gown(): View
    {
        return view('collections.gown');
    }

    public function indoWestern(): View
    {
        return view('collections.indo-western');
    }

    public function fusionWear(): View
    {
        return view('collections.fusion-wear');
    }
}
