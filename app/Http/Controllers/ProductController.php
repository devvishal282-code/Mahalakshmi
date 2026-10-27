<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = max(0, $request->integer('draw', 1));
        $start = max(0, $request->integer('start', 0));
        $length = min(100, max(1, $request->integer('length', 10)));
        $search = trim($request->string('search.value')->toString());

        $query = Product::query()
            ->with(['category:id,name']);
        $totalRecords = (clone $query)->count();

        if ($search !== '') {
            $query->where(function ($productQuery) use ($search): void {
                $productQuery
                    ->where('products.name', 'LIKE', "%{$search}%")
                    ->orWhere('products.description', 'LIKE', "%{$search}%")
                    ->orWhere('products.status', 'LIKE', "%{$search}%")
                    ->orWhere('products.price', 'LIKE', "%{$search}%")
                    ->orWhere('products.stock_quantity', 'LIKE', "%{$search}%")
                    ->orWhere('products.image_path', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search): void {
                        $categoryQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $filteredRecords = (clone $query)->count();
        $columnIndex = $request->integer('order.0.column', -1);
        $columnName = $request->input("columns.{$columnIndex}.data");
        $direction = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $sortableColumns = [
            'name' => 'products.name',
            'description' => 'products.description',
            'price' => 'products.price',
            'stock_quantity' => 'products.stock_quantity',
            'image_path' => 'products.image_path',
            'status' => 'products.status',
            'show_marketplace' => 'products.show_marketplace',
        ];

        if ($columnName === 'category_name') {
            $query->orderBy(
                Category::query()
                    ->select('name')
                    ->whereColumn('categories.id', 'products.category_id'),
                $direction
            );
        } elseif (is_string($columnName) && isset($sortableColumns[$columnName])) {
            $query->orderBy($sortableColumns[$columnName], $direction);
        } else {
            $query->orderByDesc('products.id');
        }

        $products = $query
            ->skip($start)
            ->take($length)
            ->get([
                'products.id',
                'products.category_id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock_quantity',
                'products.image_path',
                'products.status',
                'products.show_marketplace',
            ]);

        $productData = $products->map(fn (Product $product): array => [
            'name' => $product->name,
            'category_name' => $product->category?->name ?? 'Uncategorized',
            'description' => $product->description,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'image_path' => $product->image_path,
            'status' => $product->status,
            'show_marketplace' => $product->show_marketplace,
            'actions' => sprintf(
                '<div class="admin-product-actions">
                    <a class="admin-product-action-button admin-product-edit-button" href="%s" aria-label="Edit %s" title="Edit">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14 5 5 5M4 20l4.2-.8L19 8.4 15.6 5 4.8 15.8 4 20Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>
                    </a>
                    <form method="POST" action="%s" class="admin-product-delete-form" data-swal-confirm="Are you sure you want to delete this product?">
                        <input type="hidden" name="_token" value="%s">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="admin-product-action-button admin-product-delete-button" type="submit" aria-label="Delete %s" title="Delete">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>
                        </button>
                    </form>
                </div>',
                e(route('admin.products.edit', $product)),
                e($product->name),
                e(route('admin.products.destroy', $product)),
                e(csrf_token()),
                e($product->name),
            ),
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $productData,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'product' => null,
        ]);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.create', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'product' => $product,
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $productData = $request->validated();
        $productData['show_marketplace'] = $request->boolean('show_marketplace');

        Product::create($productData);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product added successfully.');
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $productData = $request->validated();
        $productData['show_marketplace'] = $request->boolean('show_marketplace');

        $product->update($productData);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product deleted successfully.');
    }
}
