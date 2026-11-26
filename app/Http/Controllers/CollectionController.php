<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function index(): View
    {
        $collections = Collection::query()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        foreach ($collections as $collection) {
            $collection->setAttribute('image_url', $this->collectionImageUrl($collection->image_path));
        }

        return view('collections.index', compact('collections'));
    }

    private function collectionImageUrl(?string $imagePath): ?string
    {
        if ($imagePath === null || trim($imagePath) === '') {
            return null;
        }

        if (Str::startsWith($imagePath, ['http://', 'https://'])) {
            return $imagePath;
        }

        return asset('storage/'.$imagePath);
    }

    public function festiveFavorites(): View
    {
        return $this->collectionPage('collections.festive-favorites');
    }

    public function festiveFavoritesCollection(): View
    {
        return $this->collectionPage('collections.festive-favorites');
    }

    public function coordSets(): View
    {
        return $this->collectionPage('collections.coord-sets');
    }

    public function designerKurtas(): View
    {
        return $this->collectionPage('collections.designer-kurtas');
    }

    public function weddingEdit(): View
    {
        return $this->collectionPage('collections.wedding-edit');
    }

    public function autumnEdit(): View
    {
        return $this->collectionPage('collections.autumn-edit');
    }

    public function everydayElegance(): View
    {
        return $this->collectionPage('collections.everyday-elegance');
    }

    public function pastelStories(): View
    {
        return $this->collectionPage('collections.pastel-stories');
    }

    public function justAdded(): View
    {
        return $this->collectionPage('collections.just-added');
    }

    public function ethnicWear(): View
    {
        return $this->collectionPage('collections.ethnic-wear');
    }

    public function cordSet(): View
    {
        return $this->collectionPage('collections.cord-set');
    }

    public function gown(): View
    {
        return $this->collectionPage('collections.gown');
    }

    public function indoWestern(): View
    {
        return $this->collectionPage('collections.indo-western');
    }

    public function fusionWear(): View
    {
        return $this->collectionPage('collections.fusion-wear');
    }

    private function collectionPage(string $routeName): View
    {
        $collection = Collection::query()
            ->where('route_name', $routeName)
            ->where('status', 'active')
            ->firstOrFail();

        $products = $collection->products()
            ->where('products.status', 'active')
            ->where('products.show_marketplace', true)
            ->with(['category:id,name', 'primaryImage'])
            ->latest('products.created_at')
            ->get();

        return view('collections.show', compact('collection', 'products'));
    }
}
