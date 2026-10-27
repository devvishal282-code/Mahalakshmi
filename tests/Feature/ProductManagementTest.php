<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests to admin login when they open product management', function () {
    $this->get(route('admin.products.index'))
        ->assertRedirect(route('admin'));
});

it('forbids authenticated non-admins from product management', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.products.index'))
        ->assertForbidden();
});

it('renders the product list and add product link for admins', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Products')
        ->assertSee('Add Product')
        ->assertSee('No data found')
        ->assertSee('products-table')
        ->assertSee("$('#products-table').DataTable", false)
        ->assertSee('Please wait, loading products...')
        ->assertSee('sweetalert2@11', false)
        ->assertSee('Swal.fire({', false)
        ->assertSee('data-swal-confirm', false)
        ->assertDontSee('<th>ID</th>', false);

    $this->get(route('admin.products.create'))
        ->assertOk()
        ->assertSee('Save Product')
        ->assertSee('No category')
        ->assertSee('type="radio"', false)
        ->assertSee('value="draft" required checked', false)
        ->assertSee('role="switch"', false);
});

it('returns products through the server-side DataTables endpoint', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $category = Category::create(['name' => 'Festive Wear']);
    Product::create([
        'category_id' => $category->id,
        'name' => 'Silk Kurta',
        'description' => 'Festive wear style',
        'price' => '2499.99',
        'stock_quantity' => 4,
        'image_path' => 'products/silk-kurta.jpg',
        'status' => 'active',
        'show_marketplace' => true,
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.products.data', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
        ]))
        ->assertOk()
        ->assertJsonPath('recordsTotal', 1)
        ->assertJsonPath('recordsFiltered', 1)
        ->assertJsonPath('data.0.name', 'Silk Kurta')
        ->assertJsonPath('data.0.category_name', 'Festive Wear')
        ->assertJsonPath('data.0.description', 'Festive wear style')
        ->assertJsonPath('data.0.image_path', 'products/silk-kurta.jpg')
        ->assertJsonPath('data.0.show_marketplace', true)
        ->assertJsonPath('data.0.actions', fn (string $actions): bool => str_contains($actions, 'admin-product-edit-button')
            && str_contains($actions, 'admin-product-delete-button')
            && str_contains($actions, 'data-swal-confirm=')
            && str_contains($actions, '<svg'));
});

it('updates a product from the edit form', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $product = Product::create([
        'name' => 'Old Kurta',
        'price' => '499.00',
        'stock_quantity' => 2,
        'status' => 'draft',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.products.edit', $product))
        ->assertOk()
        ->assertSee('Edit Product')
        ->assertSee('Old Kurta')
        ->assertSee('value="draft" required checked', false)
        ->assertSee('Update Product');

    $this->followingRedirects()
        ->put(route('admin.products.update', $product), [
            'name' => 'Updated Kurta',
            'price' => '699.00',
            'stock_quantity' => 5,
            'status' => 'active',
        ])
        ->assertOk()
        ->assertSee('Product updated successfully.')
        ->assertSee('admin-toast--success', false)
        ->assertSee('role="status"', false);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Kurta',
        'price' => '699.00',
        'stock_quantity' => 5,
        'show_marketplace' => false,
    ]);
});

it('deletes a product from its listing action', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $product = Product::create([
        'name' => 'Delete Kurta',
        'price' => '399.00',
        'stock_quantity' => 1,
        'status' => 'draft',
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('status', 'Product deleted successfully.');

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

it('filters products by the DataTables search term', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    Product::create([
        'name' => 'Silk Kurta',
        'price' => '2499.99',
        'stock_quantity' => 4,
        'status' => 'active',
    ]);
    Product::create([
        'name' => 'Cotton Saree',
        'price' => '1299.00',
        'stock_quantity' => 2,
        'status' => 'draft',
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.products.data', [
            'draw' => 2,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => 'Silk'],
        ]))
        ->assertOk()
        ->assertJsonPath('draw', 2)
        ->assertJsonPath('recordsTotal', 2)
        ->assertJsonPath('recordsFiltered', 1)
        ->assertJsonPath('data.0.name', 'Silk Kurta');
});

it('rejects invalid product input without creating a product', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->from(route('admin.products.create'))
        ->followingRedirects()
        ->post(route('admin.products.store'), [
            'name' => '',
            'price' => '',
            'stock_quantity' => 0,
            'status' => 'draft',
        ])
        ->assertOk()
        ->assertSee('admin-toast--error', false)
        ->assertSee('role="alert"', false)
        ->assertSee('The name field is required.');

    $this->assertDatabaseCount('products', 0);
});

it('creates a product with optional category and marketplace visibility', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'name' => 'Everyday Kurta',
            'description' => 'A lightweight everyday style.',
            'price' => '799.00',
            'stock_quantity' => 12,
            'status' => 'active',
            'show_marketplace' => '1',
        ])
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('status', 'Product added successfully.');

    $this->assertDatabaseHas('products', [
        'name' => 'Everyday Kurta',
        'category_id' => null,
        'price' => '799.00',
        'stock_quantity' => 12,
        'status' => 'active',
        'show_marketplace' => true,
    ]);

    $this->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Product added successfully.')
        ->assertSee('admin-toast--success', false)
        ->assertSee('role="status"', false);
});

it('keeps new products hidden from the marketplace when the checkbox is unchecked', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'name' => 'Draft Kurta',
            'price' => '599.00',
            'stock_quantity' => 3,
            'status' => 'draft',
        ])
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'name' => 'Draft Kurta',
        'show_marketplace' => false,
    ]);
});

it('rejects a product with a category that does not exist', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->from(route('admin.products.create'))
        ->post(route('admin.products.store'), [
            'category_id' => 999,
            'name' => 'Uncategorized Kurta',
            'price' => '499.00',
            'stock_quantity' => 1,
            'status' => 'draft',
        ])
        ->assertRedirect(route('admin.products.create'))
        ->assertSessionHasErrors('category_id');

    $this->assertDatabaseCount('products', 0);
});
