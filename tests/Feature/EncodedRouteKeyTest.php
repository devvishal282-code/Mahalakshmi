<?php

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('uses encoded identifiers for product category and collection URLs', function () {
    $category = Category::create(['name' => 'Encoded Category', 'status' => 'active']);
    $collection = Collection::create([
        'title' => 'Encoded Collection',
        'slug' => 'encoded-collection',
        'description' => 'A collection used to verify encoded routes.',
        'image_path' => 'collections/encoded.jpg',
        'route_name' => 'collections.festive-favorites',
        'status' => 'active',
    ]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Encoded Product',
        'price' => 999,
        'stock_quantity' => 1,
        'status' => 'active',
        'show_marketplace' => true,
    ]);

    expect(route('products.show', $product))->not->toEndWith('/'.$product->id)
        ->and(route('admin.products.update', $product))->not->toEndWith('/'.$product->id)
        ->and(route('admin.categories.edit', $category))->not->toContain('/'.$category->id.'/edit')
        ->and(route('admin.collections.edit', $collection))->not->toContain('/'.$collection->id.'/edit');

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('Encoded Product');

    $this->get('/products/'.$product->id)->assertNotFound();
});

it('resolves encoded admin URLs and rejects raw IDs', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create(['name' => 'Private Category', 'status' => 'active']);
    $collection = Collection::create([
        'title' => 'Private Collection',
        'slug' => 'private-collection',
        'description' => 'A collection used to verify encoded admin routes.',
        'image_path' => 'collections/private.jpg',
        'route_name' => 'collections.festive-favorites',
        'status' => 'active',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.categories.edit', $category))
        ->assertOk()
        ->assertSee('Private Category');

    $this->getJson(route('admin.collections.edit', $collection))
        ->assertOk()
        ->assertJsonPath('title', 'Edit Collection');

    $this->get('/admin/categories/'.$category->id.'/edit')->assertNotFound();
    $this->getJson('/admin/collections/'.$collection->id.'/edit')->assertNotFound();
});
