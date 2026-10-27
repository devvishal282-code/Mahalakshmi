<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $categories->map(fn (Category $category): array => [
                'name' => $category->name,
                'status' => $category->status,
                'products_count' => $category->products_count,
                'created_at' => $category->created_at?->format('d M Y') ?? '-',
                'actions' => sprintf(
                    '<div class="admin-product-actions">
                        <a class="admin-product-action-button admin-product-edit-button" href="%s" aria-label="Edit %s" title="Edit">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14 5 5 5M4 20l4.2-.8L19 8.4 15.6 5 4.8 15.8 4 20Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>
                        </a>
                        <form method="POST" action="%s" class="admin-product-delete-form" data-swal-confirm="Are you sure you want to delete this category? Products in this category will become uncategorized.">
                            <input type="hidden" name="_token" value="%s">
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="admin-product-action-button admin-product-delete-button" type="submit" aria-label="Delete %s" title="Delete">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>
                            </button>
                        </form>
                    </div>',
                    e(route('admin.categories.edit', $category)),
                    e($category->name),
                    e(route('admin.categories.destroy', $category)),
                    e(csrf_token()),
                    e($category->name),
                ),
            ]),
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
