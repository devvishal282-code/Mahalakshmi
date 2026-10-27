<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests to admin login when they open category management', function () {
    $this->get(route('admin.categories.index'))
        ->assertRedirect(route('admin'));
});

it('forbids authenticated non-admins from category management', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertForbidden();
});

it('renders the category list and add category form for admins', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Categories')
        ->assertSee('Add Category')
        ->assertSee('No data found')
        ->assertSee('categories-table')
        ->assertSee('<th>Action</th>', false)
        ->assertSee("$('#categories-table').DataTable", false)
        ->assertSee('Please wait, loading categories...')
        ->assertSee('sweetalert2@11', false)
        ->assertSee('Swal.fire({', false)
        ->assertSee('data-swal-confirm', false);

    $this->get(route('admin.categories.create'))
        ->assertOk()
        ->assertSee('Save Category')
        ->assertSee('Category name')
        ->assertSee('type="radio"', false)
        ->assertSee('value="active" required checked', false);
});

it('returns categories and product counts through the DataTables endpoint', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $category = Category::create(['name' => 'Festive Wear', 'status' => 'active']);
    Category::create(['name' => 'Everyday Wear']);
    Product::create([
        'category_id' => $category->id,
        'name' => 'Silk Kurta',
        'price' => '2499.99',
        'stock_quantity' => 4,
        'status' => 'active',
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.categories.data', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => 'Festive'],
            'columns' => [['data' => 'name']],
            'order' => [['column' => 0, 'dir' => 'asc']],
        ]))
        ->assertOk()
        ->assertJsonPath('recordsTotal', 2)
        ->assertJsonPath('recordsFiltered', 1)
        ->assertJsonPath('data.0.name', 'Festive Wear')
        ->assertJsonPath('data.0.status', 'active')
        ->assertJsonPath('data.0.products_count', 1)
        ->assertJsonPath('data.0.created_at', $category->created_at->format('d M Y'))
        ->assertJsonPath('data.0.actions', fn (string $actions): bool => str_contains($actions, 'admin-product-edit-button')
            && str_contains($actions, 'admin-product-delete-button')
            && str_contains($actions, 'data-swal-confirm=')
            && str_contains($actions, route('admin.categories.edit', $category))
            && str_contains($actions, route('admin.categories.destroy', $category)));
});

it('creates a category for admins', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Wedding Edit',
            'status' => 'active',
        ])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status', 'Category added successfully.');

    $this->assertDatabaseHas('categories', [
        'name' => 'Wedding Edit',
        'status' => 'active',
    ]);

    $this->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Category added successfully.')
        ->assertSee('admin-toast--success', false)
        ->assertSee('role="status"', false);
});

it('rejects a category with a blank name', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->from(route('admin.categories.create'))
        ->followingRedirects()
        ->post(route('admin.categories.store'), [
            'name' => '',
            'status' => 'active',
        ])
        ->assertOk()
        ->assertSee('admin-toast--error', false)
        ->assertSee('role="alert"', false)
        ->assertSee('The name field is required.');

    $this->assertDatabaseCount('categories', 0);
});

it('rejects a category name that already exists', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    Category::create(['name' => 'Festive Wear']);

    $this->actingAs($admin)
        ->from(route('admin.categories.create'))
        ->post(route('admin.categories.store'), [
            'name' => 'Festive Wear',
            'status' => 'active',
        ])
        ->assertRedirect(route('admin.categories.create'))
        ->assertSessionHasErrors([
            'name' => 'The name has already been taken.',
        ]);

    $this->assertDatabaseCount('categories', 1);
});

it('rejects a category with an unsupported status', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->from(route('admin.categories.create'))
        ->post(route('admin.categories.store'), [
            'name' => 'Invalid Status',
            'status' => 'archived',
        ])
        ->assertRedirect(route('admin.categories.create'))
        ->assertSessionHasErrors('status');

    $this->assertDatabaseCount('categories', 0);
});

it('updates a category and keeps its current name valid', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $category = Category::create(['name' => 'Wedding Edit', 'status' => 'active']);

    $this->actingAs($admin)
        ->get(route('admin.categories.edit', $category))
        ->assertOk()
        ->assertSee('Edit Category')
        ->assertSee('value="Wedding Edit"', false)
        ->assertSee('value="active" required checked', false)
        ->assertSee('Update Category');

    $this->followingRedirects()
        ->put(route('admin.categories.update', $category), [
            'name' => 'Wedding Edit',
            'status' => 'draft',
        ])
        ->assertOk()
        ->assertSee('Category updated successfully.')
        ->assertSee('admin-toast--success', false)
        ->assertSee('role="status"', false);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Wedding Edit',
        'status' => 'draft',
    ]);
});

it('rejects an update when another category already has the requested name', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $category = Category::create(['name' => 'Wedding Edit', 'status' => 'active']);
    Category::create(['name' => 'Festive Wear', 'status' => 'active']);

    $this->actingAs($admin)
        ->from(route('admin.categories.edit', $category))
        ->put(route('admin.categories.update', $category), [
            'name' => 'Festive Wear',
            'status' => 'draft',
        ])
        ->assertRedirect(route('admin.categories.edit', $category))
        ->assertSessionHasErrors([
            'name' => 'The name has already been taken.',
        ]);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Wedding Edit',
        'status' => 'active',
    ]);
});

it('deletes a category and leaves its products uncategorized', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $category = Category::create(['name' => 'Festive Wear', 'status' => 'active']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Silk Kurta',
        'price' => '2499.99',
        'stock_quantity' => 4,
        'status' => 'active',
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status', 'Category deleted successfully.');

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'category_id' => null,
    ]);
});
