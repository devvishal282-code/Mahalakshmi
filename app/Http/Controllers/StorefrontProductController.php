<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class StorefrontProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_unless($product->status === 'active' && $product->show_marketplace, 404);

        $product->load(['category:id,name', 'collections:id,title,route_name', 'images']);

        $relatedProducts = Product::query()
            ->with(['category:id,name', 'primaryImage'])
            ->whereKeyNot($product->id)
            ->where('status', 'active')
            ->where('show_marketplace', true)
            ->when(
                $product->category_id,
                fn ($query) => $query->where('category_id', $product->category_id)
            )
            ->latest()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
