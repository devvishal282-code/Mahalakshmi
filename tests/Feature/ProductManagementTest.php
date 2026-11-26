<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

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

it('renders the product list and add product button for admins', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Products')
        ->assertSee('Add Product')
        ->assertSee('data-product-modal-open', false)
        ->assertSee('<dialog id="product-modal"', false)
        ->assertSee('No data found')
        ->assertSee('products-table')
        ->assertSee('<th>Image</th>', false)
        ->assertSee('admin-product-table-image', false)
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
        ->assertSee('role="switch"', false)
        ->assertSee('name="product_images[]"', false)
        ->assertSee('admin-product-file-picker-button', false)
        ->assertSee('id="product-image-gallery"', false)
        ->assertSee('name="primary_image"', false)
        ->assertSee('admin-product-collections-select', false)
        ->assertDontSee('name="image_path"', false);
});

it('returns the add product modal form as JSON', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    Category::create(['name' => 'Festive Wear']);

    $this->actingAs($admin)
        ->getJson(route('admin.products.create'))
        ->assertOk()
        ->assertJsonPath('title', 'Add Product')
        ->assertJsonPath('html', fn (string $html): bool => str_contains($html, 'id="product-form"')
            && str_contains($html, route('admin.products.store'))
            && str_contains($html, 'Festive Wear')
            && str_contains($html, 'data-product-modal-close')
            && str_contains($html, 'name="product_images[]"')
            && str_contains($html, 'admin-product-file-picker-button')
            && str_contains($html, 'id="product-image-gallery"')
            && str_contains($html, 'name="primary_image"')
            && str_contains($html, 'admin-product-collections-select')
            && str_contains($html, 'admin-product-form-content')
            && str_contains($html, 'admin-product-form-actions')
            && ! str_contains($html, 'name="image_path"'));
});

it('returns products through the server-side DataTables endpoint', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $category = Category::create(['name' => 'Festive Wear']);
    Storage::disk('public')->put('products/silk-kurta.jpg', 'image content');
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Silk Kurta',
        'description' => 'Festive wear style',
        'price' => '2499.99',
        'stock_quantity' => 4,
        'image_path' => 'products/silk-kurta.jpg',
        'status' => 'active',
        'show_marketplace' => true,
    ]);
    ProductImage::create([
        'product_id' => $product->id,
        'image_path' => 'products/silk-kurta.jpg',
        'is_primary' => true,
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
        ->assertJsonPath('data.0.image_url', Storage::disk('public')->url('products/silk-kurta.jpg'))
        ->assertJsonPath('data.0.show_marketplace', true)
        ->assertJsonPath('data.0.actions', fn (string $actions): bool => str_contains($actions, 'editProduct')
            && str_contains($actions, 'deleteProduct')
            && str_contains($actions, 'fa-solid fa-edit')
            && str_contains($actions, 'fa-solid fa-trash-can'));
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
    $productImage = ProductImage::create([
        'product_id' => $product->id,
        'image_path' => 'products/old-kurta.jpg',
        'is_primary' => true,
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.products.edit', ['id' => Crypt::encrypt($product->id)]))
        ->assertOk()
        ->assertJsonPath('title', 'Edit Product')
        ->assertJsonPath('html', fn (string $html): bool => str_contains($html, 'Old Kurta')
            && str_contains($html, 'value="draft" required checked')
            && str_contains($html, route('admin.products.update', $product)));

    $this->followingRedirects()
        ->put(route('admin.products.update', $product), [
            'name' => 'Updated Kurta',
            'price' => '699.00',
            'stock_quantity' => 5,
            'status' => 'active',
            'primary_image' => 'existing:'.$productImage->id,
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

it('does not open a product edit form with an invalid encrypted identifier', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->getJson(route('admin.products.edit', ['id' => 'invalid']))
        ->assertNotFound();
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
        ->deleteJson(route('admin.products.destroy'), [
            'id' => Crypt::encrypt($product->id),
        ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Product Deleted Successfully.');

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
    Storage::fake('public');
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
            'product_images' => [UploadedFile::fake()->image('everyday.jpg')],
            'primary_image' => 'new:0',
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
    Storage::fake('public');
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'name' => 'Draft Kurta',
            'price' => '599.00',
            'stock_quantity' => 3,
            'status' => 'draft',
            'product_images' => [UploadedFile::fake()->image('draft.jpg')],
            'primary_image' => 'new:0',
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

it('creates and updates products with AJAX JSON responses', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->postJson(route('admin.products.store'), [
            'name' => 'AJAX Kurta',
            'price' => '899.00',
            'stock_quantity' => 6,
            'status' => 'draft',
            'product_images' => [UploadedFile::fake()->image('ajax.jpg')],
            'primary_image' => 'new:0',
        ])
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Product added successfully.');

    $product = Product::query()->where('name', 'AJAX Kurta')->firstOrFail();
    $productImage = $product->images()->firstOrFail();

    $this->putJson(route('admin.products.update', $product), [
        'name' => 'Updated AJAX Kurta',
        'price' => '999.00',
        'stock_quantity' => 8,
        'status' => 'active',
        'primary_image' => 'existing:'.$productImage->id,
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Product updated successfully.');

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated AJAX Kurta',
        'price' => '999.00',
        'stock_quantity' => 8,
    ]);
});

it('stores multiple product images and safely replaces a removed primary image', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'name' => 'Image Kurta',
            'price' => '899.00',
            'stock_quantity' => 6,
            'status' => 'draft',
            'product_images' => [
                UploadedFile::fake()->image('kurta.png'),
                UploadedFile::fake()->image('kurta-detail.jpg'),
            ],
            'primary_image' => 'new:0',
        ], ['Accept' => 'application/json'])
        ->assertCreated()
        ->assertJsonPath('success', true);

    $product = Product::query()->where('name', 'Image Kurta')->firstOrFail();
    $firstImagePath = $product->image_path;
    $firstImage = $product->images()->where('is_primary', true)->firstOrFail();

    expect(str_starts_with($firstImagePath, 'products/'))->toBeTrue();
    expect($product->images()->count())->toBe(2);
    Storage::disk('public')->assertExists($firstImagePath);

    $editResponse = $this->getJson(route('admin.products.edit', [
        'id' => Crypt::encrypt($product->id),
    ]))->assertOk();

    $editResponse->assertJsonPath('html', fn (string $html): bool => str_contains($html, 'name="product_images[]"')
        && str_contains($html, 'value="existing:'.$firstImage->id.'"')
        && str_contains($html, Storage::disk('public')->url($firstImagePath)));

    $this->post(route('admin.products.update', $product), [
        '_method' => 'PUT',
        'name' => 'Image Kurta',
        'price' => '899.00',
        'stock_quantity' => 6,
        'status' => 'draft',
        'product_images' => [UploadedFile::fake()->image('replacement.webp')],
        'removed_image_ids' => [$firstImage->id],
        'primary_image' => 'new:0',
    ], ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonPath('success', true);

    $product->refresh();
    expect($product->image_path)->not->toBe($firstImagePath);
    expect($product->images()->where('is_primary', true)->count())->toBe(1);
    Storage::disk('public')->assertExists($product->image_path);
    Storage::disk('public')->assertMissing($firstImagePath);
});

it('keeps the existing image when a product is updated without a replacement file', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();
    $imagePath = UploadedFile::fake()->image('existing.png')->store('products', 'public');
    $product = Product::create([
        'name' => 'Existing Image Kurta',
        'price' => '899.00',
        'stock_quantity' => 6,
        'image_path' => $imagePath,
        'status' => 'draft',
    ]);
    $productImage = ProductImage::create([
        'product_id' => $product->id,
        'image_path' => $imagePath,
        'is_primary' => true,
    ]);

    $this->actingAs($admin)
        ->putJson(route('admin.products.update', $product), [
            'name' => 'Updated Existing Image Kurta',
            'price' => '999.00',
            'stock_quantity' => 8,
            'status' => 'active',
            'primary_image' => 'existing:'.$productImage->id,
        ])
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Existing Image Kurta',
        'image_path' => $imagePath,
    ]);
    Storage::disk('public')->assertExists($imagePath);
});

it('rejects non-image product uploads', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'name' => 'Invalid Image Kurta',
            'price' => '899.00',
            'stock_quantity' => 6,
            'status' => 'draft',
            'product_images' => [UploadedFile::fake()->create('notes.txt', 10, 'text/plain')],
            'primary_image' => 'new:0',
        ], ['Accept' => 'application/json'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('product_images.0');

    $this->assertDatabaseCount('products', 0);
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
});

it('rejects product images larger than 2 MB', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'name' => 'Oversized Image Kurta',
            'price' => '899.00',
            'stock_quantity' => 6,
            'status' => 'draft',
            'product_images' => [UploadedFile::fake()->image('oversized.png')->size(2049)],
            'primary_image' => 'new:0',
        ], ['Accept' => 'application/json'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('product_images.0');

    $this->assertDatabaseCount('products', 0);
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
});

it('returns validation errors as JSON when AJAX product data is invalid', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    $this->actingAs($admin)
        ->postJson(route('admin.products.store'), [
            'name' => '',
            'price' => '499.00',
            'stock_quantity' => 1,
            'status' => 'draft',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('name');

    $this->assertDatabaseCount('products', 0);
});
