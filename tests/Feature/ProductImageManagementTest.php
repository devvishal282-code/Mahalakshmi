<?php

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('requires a valid primary image when creating a product', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->postJson(route('admin.products.store'), [
            'name' => 'Primary Required Set',
            'price' => '1499.00',
            'stock_quantity' => 2,
            'status' => 'draft',
            'product_images' => [UploadedFile::fake()->image('front.jpg')],
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('primary_image');

    $this->assertDatabaseMissing('products', ['name' => 'Primary Required Set']);
});

it('stores multiple local images with one primary image and multiple collections', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $collections = collect([
        Collection::create(['title' => 'Festive', 'slug' => 'festive', 'description' => 'Festive collection', 'image_path' => 'collections/festive.jpg', 'route_name' => 'collections.festive-favorites']),
        Collection::create(['title' => 'Wedding', 'slug' => 'wedding', 'description' => 'Wedding collection', 'image_path' => 'collections/wedding.jpg', 'route_name' => 'collections.wedding-edit']),
    ]);

    $this->actingAs($admin)
        ->postJson(route('admin.products.store'), [
            'name' => 'Rose Celebration Set',
            'price' => '2499.00',
            'stock_quantity' => 4,
            'status' => 'active',
            'show_marketplace' => true,
            'collection_ids' => $collections->modelKeys(),
            'product_images' => [
                UploadedFile::fake()->image('front.jpg'),
                UploadedFile::fake()->image('detail.webp'),
            ],
            'primary_image' => 'new:1',
        ])
        ->assertCreated();

    $product = Product::query()->where('name', 'Rose Celebration Set')->firstOrFail();
    $images = $product->images()->get();

    expect($images)->toHaveCount(2)
        ->and($images->where('is_primary', true))->toHaveCount(1)
        ->and($product->collections()->count())->toBe(2)
        ->and($product->image_path)->toBe($images->firstWhere('is_primary', true)->image_path);

    $images->each(fn (ProductImage $image) => Storage::disk('public')->assertExists($image->image_path));
});

it('changes the primary image without deleting existing gallery images', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::create([
        'name' => 'Ivory Set',
        'price' => '1999.00',
        'stock_quantity' => 3,
        'status' => 'active',
    ]);
    $front = $product->images()->create(['image_path' => 'products/front.jpg', 'is_primary' => true, 'sort_order' => 0]);
    $detail = $product->images()->create(['image_path' => 'products/detail.jpg', 'is_primary' => false, 'sort_order' => 1]);
    Storage::disk('public')->put($front->image_path, 'front');
    Storage::disk('public')->put($detail->image_path, 'detail');

    $this->actingAs($admin)
        ->putJson(route('admin.products.update', $product), [
            'name' => $product->name,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'status' => $product->status,
            'primary_image' => 'existing:'.$detail->id,
        ])
        ->assertOk();

    expect($product->images()->count())->toBe(2)
        ->and($product->images()->where('is_primary', true)->value('id'))->toBe($detail->id)
        ->and($product->fresh()->image_path)->toBe($detail->image_path);
    Storage::disk('public')->assertExists($front->image_path);
    Storage::disk('public')->assertExists($detail->image_path);
});

it('deletes all local image files when a product is deleted', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::create(['name' => 'Delete Set', 'price' => 999, 'stock_quantity' => 1, 'status' => 'draft']);
    $image = $product->images()->create(['image_path' => 'products/delete-set.jpg', 'is_primary' => true]);
    Storage::disk('public')->put($image->image_path, 'image');

    $this->actingAs($admin)
        ->deleteJson(route('admin.products.destroy'), ['id' => Crypt::encrypt($product->id)])
        ->assertOk();

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
    Storage::disk('public')->assertMissing($image->image_path);
});

it('renders a dynamic product gallery and related products', function () {
    Storage::fake('public');
    $category = Category::create(['name' => 'Festive Wear', 'status' => 'active']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Lavender Festive Suit',
        'description' => 'A graceful festive suit.',
        'price' => 2499,
        'stock_quantity' => 8,
        'status' => 'active',
        'show_marketplace' => true,
    ]);
    $product->images()->create(['image_path' => 'products/lavender-front.jpg', 'is_primary' => true, 'sort_order' => 0]);
    $product->images()->create(['image_path' => 'products/lavender-detail.jpg', 'is_primary' => false, 'sort_order' => 1]);
    $related = Product::create([
        'category_id' => $category->id,
        'name' => 'Related Festive Kurta',
        'price' => 1899,
        'stock_quantity' => 2,
        'status' => 'active',
        'show_marketplace' => true,
    ]);
    $related->images()->create(['image_path' => 'products/related.jpg', 'is_primary' => true]);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('Lavender Festive Suit')
        ->assertSee('A graceful festive suit.')
        ->assertSee('₹2,499.00')
        ->assertSee('data-product-gallery', false)
        ->assertSee('data-thumbnail-slider', false)
        ->assertSee('data-thumbnail-previous', false)
        ->assertSee('data-thumbnail-next', false)
        ->assertSee('data-gallery-thumbnail', false)
        ->assertSee('products/lavender-front.jpg')
        ->assertSee('products/lavender-detail.jpg')
        ->assertSee('Related Festive Kurta')
        ->assertSee('Enquire on WhatsApp');
});

it('does not expose draft or marketplace-hidden product pages', function () {
    $draft = Product::create(['name' => 'Private Draft', 'price' => 500, 'stock_quantity' => 1, 'status' => 'draft', 'show_marketplace' => true]);
    $hidden = Product::create(['name' => 'Hidden Product', 'price' => 500, 'stock_quantity' => 1, 'status' => 'active', 'show_marketplace' => false]);

    $this->get(route('products.show', $draft))->assertNotFound();
    $this->get(route('products.show', $hidden))->assertNotFound();
});
