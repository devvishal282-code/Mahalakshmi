<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = max(0, $request->integer('draw', 1));
        $start = max(0, $request->integer('start', 0));
        $length = min(100, max(1, $request->integer('length', 10)));
        $search = trim($request->string('search.value')->toString());

        $query = Category::query();
        $totalRecords = (clone $query)->count();

        if ($search !== '') {
            $query->where('categories.name', 'LIKE', "%{$search}%");
        }

        $filteredRecords = (clone $query)->count();
        $columnIndex = $request->integer('order.0.column', -1);
        $columnName = $request->input("columns.{$columnIndex}.data");
        $direction = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $sortableColumns = [
            'name' => 'categories.name',
            'status' => 'categories.status',
            'products_count' => 'products_count',
            'created_at' => 'categories.created_at',
        ];

        $query->select(['categories.id', 'categories.name', 'categories.status', 'categories.created_at'])
            ->withCount('products');

        if (is_string($columnName) && isset($sortableColumns[$columnName])) {
            $query->orderBy($sortableColumns[$columnName], $direction);
        } else {
            $query->orderByDesc('categories.id');
        }

        $categories = $query
            ->skip($start)
            ->take($length)
            ->get();
        $categoryData = $categories->map(function ($category) {
            $encryptedId = Crypt::encrypt($category->id);
            $action = '';
            $action .= '<a class="act-btn me-2 editCategory ms-2" data-id="'.$encryptedId.'" data-bs-toggle="tooltip" data-placement="top" title="'.__('admin.edit').'"><i class="fa-solid fa-edit" aria-hidden="true"></i></a>';
            $action .= '<a href="javascript:void(0)" id="deleteCategory" class="act-btn del deleteCategory" data-id="'.$encryptedId.'" data-bs-toggle="tooltip" data-placement="top" title="'.__('admin.delete').'"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></a>';

            return [
                'name' => $category->name,
                'status' => $category->status,
                'products_count' => $category->products_count,
                'created_at' => $category->created_at?->format('d M Y') ?? '-',
                'actions' => $action,
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $categoryData,
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'category' => null,
        ]);
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.create', [
            'category' => $category,
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Category added successfully.');
    }

    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Category deleted successfully.');
    }
}
