<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

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
            ->with(['category:id,name', 'collections:id,title', 'primaryImage']);
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
                    })
                    ->orWhereHas('collections', function ($collectionQuery) use ($search): void {
                        $collectionQuery->where('title', 'LIKE', "%{$search}%");
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
                'collection_names' => $product->collections->pluck('title')->implode(', '),
                'description' => $product->description,
                'price' => $product->price,
                'stock_quantity' => $product->stock_quantity,
                'image_path' => $product->primaryImage?->image_path,
                'image_url' => $this->productImagePreviewUrl($product->primaryImage?->image_path),
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

        $product = Product::query()->with('images')->findOrFail($productId);

        return $this->productFormResponse($request, $product);
    }

    public function store(StoreProductRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $collectionIds = array_values(array_filter($validated['collection_ids'] ?? []));
        $productData = Arr::except($validated, [
            'collection_ids',
            'product_images',
            'removed_image_ids',
            'primary_image',
        ]);
        $productData['show_marketplace'] = $request->boolean('show_marketplace');
        $storedPaths = [];

        try {
            foreach ($request->file('product_images', []) as $image) {
                $storedPaths[] = $this->storeProductImage($image);
            }

            $primaryIndex = (int) Str::after((string) $validated['primary_image'], 'new:');

            DB::transaction(function () use ($productData, $collectionIds, $storedPaths, $primaryIndex): void {
                $product = Product::create($productData);
                $product->collections()->sync($collectionIds);

                foreach ($storedPaths as $index => $imagePath) {
                    $product->images()->create([
                        'image_path' => $imagePath,
                        'is_primary' => $index === $primaryIndex,
                        'sort_order' => $index,
                    ]);
                }

                $product->update(['image_path' => $storedPaths[$primaryIndex]]);
            });
        } catch (Throwable $exception) {
            $this->deleteProductImages($storedPaths);

            throw $exception;
        }

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
        $validated = $request->validated();
        $collectionIds = array_values(array_filter($validated['collection_ids'] ?? []));
        $removedImageIds = array_map('intval', $validated['removed_image_ids'] ?? []);
        $productData = Arr::except($validated, [
            'collection_ids',
            'product_images',
            'removed_image_ids',
            'primary_image',
        ]);
        $productData['show_marketplace'] = $request->boolean('show_marketplace');
        $newPaths = [];
        $removedPaths = [];

        try {
            foreach ($request->file('product_images', []) as $image) {
                $newPaths[] = $this->storeProductImage($image);
            }

            DB::transaction(function () use ($product, $productData, $collectionIds, $removedImageIds, $newPaths, $validated, &$removedPaths): void {
                $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);
                $removedImages = $lockedProduct->images()->whereKey($removedImageIds)->get();
                $removedPaths = $removedImages->pluck('image_path')->all();
                $removedImages->each->delete();

                $lockedProduct->images()->update(['is_primary' => false]);
                $nextSortOrder = (int) $lockedProduct->images()->max('sort_order') + 1;
                $newImageIds = [];

                foreach ($newPaths as $index => $imagePath) {
                    $image = $lockedProduct->images()->create([
                        'image_path' => $imagePath,
                        'is_primary' => false,
                        'sort_order' => $nextSortOrder + $index,
                    ]);
                    $newImageIds[$index] = $image->id;
                }

                [$primaryType, $primaryIdentifier] = explode(':', (string) $validated['primary_image'], 2);
                $primaryImageId = $primaryType === 'existing'
                    ? (int) $primaryIdentifier
                    : $newImageIds[(int) $primaryIdentifier];
                $primaryImage = $lockedProduct->images()->whereKey($primaryImageId)->firstOrFail();
                $primaryImage->update(['is_primary' => true]);

                $lockedProduct->update([
                    ...$productData,
                    'image_path' => $primaryImage->image_path,
                ]);
                $lockedProduct->collections()->sync($collectionIds);
            });
        } catch (Throwable $exception) {
            $this->deleteProductImages($newPaths);

            throw $exception;
        }

        $this->deleteProductImages($removedPaths);

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

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate(['id' => ['required', 'string']]);

        try {
            $productId = Crypt::decrypt($validated['id']);
        } catch (DecryptException) {
            abort(404);
        }

        $product = Product::query()->with('images')->findOrFail($productId);
        $imagePaths = $product->images->pluck('image_path')->all();

        DB::transaction(fn () => $product->forceDelete());
        $this->deleteProductImages($imagePaths);

        return response()->json(['success' => true, 'message' => 'Product Deleted Successfully.']);
    }

    private function productFormResponse(Request $request, ?Product $product): View|JsonResponse
    {
        $viewData = [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'collections' => Collection::query()->orderBy('sort_order')->orderBy('title')->get(['id', 'title', 'status']),
            'product' => $product,
            'selectedCollectionIds' => $product ? $product->collections()->pluck('collections.id')->all() : [],
            'productImages' => $product?->images ?? collect(),
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

    /** @param array<int, string> $imagePaths */
    private function deleteProductImages(array $imagePaths): void
    {
        foreach (array_unique($imagePaths) as $imagePath) {
            if (! Str::startsWith($imagePath, 'products/')) {
                continue;
            }

            if (Storage::disk('public')->exists($imagePath) && ! Storage::disk('public')->delete($imagePath)) {
                Log::warning('Unable to remove a product image.', ['image_path' => $imagePath]);
            }
        }
    }

    private function productImagePreviewUrl(?string $imagePath): ?string
    {
        if ($imagePath === null || trim($imagePath) === '') {
            return null;
        }

        if (! Str::startsWith($imagePath, 'products/')) {
            return null;
        }

        $relativePath = ltrim($imagePath, '/');

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->url($relativePath);
        }

        return null;
    }
}
