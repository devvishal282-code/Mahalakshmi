<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionRequest;
use App\Models\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class AdminCollectionController extends Controller
{
    public function index(): View
    {
        return view('admin.collections.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = max(0, $request->integer('draw', 1));
        $start = max(0, $request->integer('start', 0));
        $length = min(100, max(1, $request->integer('length', 10)));
        $search = trim($request->string('search.value')->toString());

        $query = Collection::query();
        $totalRecords = (clone $query)->count();

        if ($search !== '') {
            $query->where(function ($collectionQuery) use ($search): void {
                $collectionQuery
                    ->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $filteredRecords = (clone $query)->count();
        $columnIndex = $request->integer('order.0.column', -1);
        $columnName = $request->input("columns.{$columnIndex}.data");
        $direction = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $sortableColumns = [
            'title' => 'title',
            'status' => 'status',
            'sort_order' => 'sort_order',
            'updated_at' => 'updated_at',
        ];

        if (is_string($columnName) && isset($sortableColumns[$columnName])) {
            $query->orderBy($sortableColumns[$columnName], $direction);
        } else {
            $query->orderBy('sort_order')->orderBy('title');
        }

        $collections = $query
            ->skip($start)
            ->take($length)
            ->get([
                'id',
                'title',
                'slug',
                'image_path',
                'route_name',
                'status',
                'sort_order',
                'updated_at',
            ]);

        $collectionData = $collections->map(function (Collection $collection): array {
            $editUrl = e(route('admin.collections.edit', $collection));

            return [
                'title' => $collection->title,
                'slug' => $collection->slug,
                'image_url' => $this->collectionImageUrl($collection->image_path),
                'route_name' => $collection->route_name,
                'status' => $collection->status,
                'sort_order' => $collection->sort_order,
                'updated_at' => $collection->updated_at?->format('d M Y') ?? '-',
                'actions' => '<button class="act-btn editCollection" type="button" data-url="'.$editUrl.'" aria-label="Edit '.e($collection->title).'"><i class="fa-solid fa-edit" aria-hidden="true"></i></button>',
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $collectionData,
        ]);
    }

    public function create(): JsonResponse
    {
        return $this->formResponse(null);
    }

    public function edit(Collection $collection): JsonResponse
    {
        return $this->formResponse($collection);
    }

    public function store(StoreCollectionRequest $request): JsonResponse|RedirectResponse
    {
        $collectionData = $request->validated();
        unset($collectionData['collection_image']);

        $image = $request->file('collection_image');

        if (! $image instanceof UploadedFile) {
            throw new RuntimeException('A collection image is required.');
        }

        $collectionData['image_path'] = $this->storeCollectionImage($image);
        Collection::create($collectionData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Collection added successfully.',
            ], 201);
        }

        return redirect()
            ->route('admin.collections.index')
            ->with('status', 'Collection added successfully.');
    }

    public function update(StoreCollectionRequest $request, Collection $collection): JsonResponse|RedirectResponse
    {
        $collectionData = $request->validated();
        unset($collectionData['collection_image']);

        $previousImagePath = $collection->image_path;
        $newImagePath = null;
        $image = $request->file('collection_image');

        if ($image instanceof UploadedFile) {
            $newImagePath = $this->storeCollectionImage($image);
            $collectionData['image_path'] = $newImagePath;
        }

        $collection->update($collectionData);

        if ($newImagePath !== null && $newImagePath !== $previousImagePath) {
            $this->deleteReplacedCollectionImage($previousImagePath, $collection);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Collection updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.collections.index')
            ->with('status', 'Collection updated successfully.');
    }

    private function formResponse(?Collection $collection): JsonResponse
    {
        return response()->json([
            'title' => $collection ? 'Edit Collection' : 'Add Collection',
            'html' => view('admin.collections._form', [
                'collection' => $collection,
                'imagePreviewUrl' => $collection ? $this->collectionImageUrl($collection->image_path) : null,
                'routeOptions' => StoreCollectionRequest::routeOptions(),
            ])->render(),
        ]);
    }

    private function storeCollectionImage(UploadedFile $image): string
    {
        $imagePath = $image->store('collections', 'public');

        if (! is_string($imagePath)) {
            throw new RuntimeException('Unable to store the collection image.');
        }

        return $imagePath;
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

    private function deleteReplacedCollectionImage(?string $imagePath, Collection $collection): void
    {
        if ($imagePath === null || ! Str::startsWith($imagePath, 'collections/')) {
            return;
        }

        if (
            Storage::disk('public')->exists($imagePath)
            && ! Storage::disk('public')->delete($imagePath)
        ) {
            Log::warning('Unable to remove the replaced collection image.', [
                'collection_id' => $collection->id,
                'image_path' => $imagePath,
            ]);
        }
    }
}
