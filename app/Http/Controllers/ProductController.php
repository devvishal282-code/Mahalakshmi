<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

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
                Category::select('name')->whereColumn('categories.id', 'products.category_id'),
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

        $productData = $products->map(function ($product) {

            $encryptedId = Crypt::encrypt($product->id);
            $action = '';
            $action .= '<a class="act-btn me-2 editProduct ms-2" data-id="'.$encryptedId.'" data-bs-toggle="tooltip" data-placement="top" title="'.__('admin.edit').'"><i class="fa-solid fa-edit" aria-hidden="true"></i></a>';
            $action .= '<a href="javascript:void(0)" id="deleteProduct" class="act-btn del deleteProduct" data-id="'.$encryptedId.'" data-bs-toggle="tooltip" data-placement="top" title="'.__('admin.delete').'"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></a>';

            return [
                'name' => $product->name,
                'category_name' => $product->category?->name ?? 'Uncategorized',
                'description' => $product->description,
                'price' => $product->price,
                'stock_quantity' => $product->stock_quantity,
                'image_path' => $product->image_path,
                'image_url' => $this->productImagePreviewUrl($product->image_path),
                'status' => $product->status,
                'show_marketplace' => $product->show_marketplace,
                'actions' => $action,
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $productData,
        ]);
    }

    public function create(Request $request): View|JsonResponse
    {
        return $this->productFormResponse($request, null);
    }

    public function edit(Request $request): View|JsonResponse
    {
        $validated = $request->validate([
            'id' => ['required', 'string'],
        ]);

        try {
            $productId = Crypt::decrypt($validated['id']);
        } catch (DecryptException) {
            abort(404);
        }

        $product = Product::findOrFail($productId);

        return $this->productFormResponse($request, $product);
    }

    public function store(StoreProductRequest $request): JsonResponse|RedirectResponse
    {
        $productData = $request->validated();
        unset($productData['product_image']);

        if ($request->hasFile('product_image')) {
            $productData['image_path'] = $this->storeProductImage($request->file('product_image'));
        }

        $productData['show_marketplace'] = $request->boolean('show_marketplace');

        Product::create($productData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added successfully.',
            ], 201);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product added successfully.');
    }

    public function update(StoreProductRequest $request, Product $product): JsonResponse|RedirectResponse
    {
        $productData = $request->validated();
        unset($productData['product_image']);

        $previousImagePath = $product->image_path;
        $newImagePath = null;

        if ($request->hasFile('product_image')) {
            $newImagePath = $this->storeProductImage($request->file('product_image'));
            $productData['image_path'] = $newImagePath;
        }

        $productData['show_marketplace'] = $request->boolean('show_marketplace');

        $product->update($productData);

        if ($newImagePath !== null && $previousImagePath !== $newImagePath) {
            $this->deleteReplacedProductImage($previousImagePath, $product);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product updated successfully.');
    }

    public function destroy(Request $request)
    {
        $product = Product::findOrFail(Crypt::decrypt($request->id));
        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product Deleted Successfully.']);
    }

    private function productFormResponse(Request $request, ?Product $product): View|JsonResponse
    {
        $viewData = [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'product' => $product,
            'imagePreviewUrl' => $this->productImagePreviewUrl($product?->image_path),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'title' => $product ? 'Edit Product' : 'Add Product',
                'html' => view('admin.products._form', [...$viewData, 'isModal' => true])->render(),
            ]);
        }

        return view('admin.products.create', $viewData);
    }

    private function storeProductImage(UploadedFile $image): string
    {
        $imagePath = $image->store('products', 'public');

        if (! is_string($imagePath)) {
            throw new RuntimeException('Unable to store the uploaded product image.');
        }

        return $imagePath;
    }

    private function deleteReplacedProductImage(?string $imagePath, Product $product): void
    {
        if ($imagePath === null || ! Str::startsWith($imagePath, 'products/')) {
            return;
        }

        if (
            Storage::disk('public')->exists($imagePath)
            && ! Storage::disk('public')->delete($imagePath)
        ) {
            Log::warning('Unable to remove the replaced product image.', [
                'product_id' => $product->id,
                'image_path' => $imagePath,
            ]);
        }
    }

    private function productImagePreviewUrl(?string $imagePath): ?string
    {
        if ($imagePath === null || trim($imagePath) === '') {
            return null;
        }

        if (Str::startsWith($imagePath, ['http://', 'https://'])) {
            return $imagePath;
        }

        $relativePath = ltrim($imagePath, '/');

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->url($relativePath);
        }

        if (is_file(public_path($relativePath))) {
            return asset($relativePath);
        }

        return null;
    }
}
