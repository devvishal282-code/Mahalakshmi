<?php

use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function collectionAdmin(): User
{
    $admin = User::factory()->create();
    $admin->forceFill(['is_admin' => true])->save();

    return $admin;
}

function collectionAttributes(array $overrides = []): array
{
    return array_merge([
        'title' => 'Festive Favourites',
        'slug' => 'festive-favorites',
        'description' => 'Occasion-ready styles for every celebration.',
        'image_path' => 'https://example.test/festive.jpg',
        'image_alt' => 'Festive Indian outfit',
        'route_name' => 'collections.festive-favorites',
        'status' => 'active',
        'sort_order' => 1,
    ], $overrides);
}

it('redirects guests to admin login when they open collection management', function () {
    $this->get(route('admin.collections.index'))
        ->assertRedirect(route('admin'));
});

it('forbids authenticated non-admins from collection management', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.collections.index'))
        ->assertForbidden();
});

it('renders the collection table and single add edit dialog for admins', function () {
    $this->actingAs(collectionAdmin())
        ->get(route('admin.collections.index'))
        ->assertOk()
        ->assertSee('Collections')
        ->assertSee('Add Collection')
        ->assertSee('collections-table')
        ->assertSee('<dialog id="collection-modal"', false)
        ->assertSee("$('#collections-table').DataTable", false)
        ->assertSee(route('admin.collections.index'))
        ->assertSee('Collection');
});

it('returns collection rows through the DataTables endpoint', function () {
    $admin = collectionAdmin();
    $collection = Collection::create(collectionAttributes());

    $this->actingAs($admin)
        ->getJson(route('admin.collections.data', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => 'Festive'],
        ]))
        ->assertOk()
        ->assertJsonPath('recordsTotal', 1)
        ->assertJsonPath('recordsFiltered', 1)
        ->assertJsonPath('data.0.title', 'Festive Favourites')
        ->assertJsonPath('data.0.slug', 'festive-favorites')
        ->assertJsonPath('data.0.route_name', 'collections.festive-favorites')
        ->assertJsonPath('data.0.status', 'active')
        ->assertJsonPath('data.0.actions', fn (string $actions): bool => str_contains($actions, 'editCollection')
            && str_contains($actions, route('admin.collections.edit', $collection)));
});

it('loads add and edit forms through the collection dialog endpoints', function () {
    $admin = collectionAdmin();
    $collection = Collection::create(collectionAttributes());

    $this->actingAs($admin)
        ->getJson(route('admin.collections.create'))
        ->assertOk()
        ->assertJsonPath('title', 'Add Collection')
        ->assertJsonPath('html', fn (string $html): bool => str_contains($html, 'id="collection-form"')
            && str_contains($html, route('admin.collections.store'))
            && str_contains($html, 'name="route_name"')
            && str_contains($html, 'name="collection_image"')
            && str_contains($html, 'type="file"'));

    $this->getJson(route('admin.collections.edit', $collection))
        ->assertOk()
        ->assertJsonPath('title', 'Edit Collection')
        ->assertJsonPath('html', fn (string $html): bool => str_contains($html, 'value="Festive Favourites"')
            && str_contains($html, route('admin.collections.update', $collection))
            && str_contains($html, 'Update Collection'));
});

it('stores and updates collection records from the admin dialog', function () {
    Storage::fake('public');
    $admin = collectionAdmin();
    $collectionData = collectionAttributes();
    unset($collectionData['image_path']);
    $collectionData['collection_image'] = UploadedFile::fake()->image('festive.jpg');

    $this->actingAs($admin)
        ->post(route('admin.collections.store'), $collectionData, ['Accept' => 'application/json'])
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Collection added successfully.');

    $collection = Collection::query()->where('slug', 'festive-favorites')->firstOrFail();
    $storedImagePath = $collection->image_path;
    expect(Storage::disk('public')->exists($storedImagePath))->toBeTrue();

    $updatedAttributes = collectionAttributes([
        'title' => 'Updated Festive Edit',
        'slug' => 'updated-festive-edit',
        'status' => 'draft',
        'sort_order' => 4,
    ]);
    unset($updatedAttributes['image_path']);

    $this->putJson(route('admin.collections.update', $collection), $updatedAttributes)
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Collection updated successfully.');

    $this->assertDatabaseHas('collections', [
        'id' => $collection->id,
        'title' => 'Updated Festive Edit',
        'slug' => 'updated-festive-edit',
        'status' => 'draft',
        'sort_order' => 4,
        'image_path' => $storedImagePath,
    ]);
});

it('rejects collection routes that are not configured storefront pages', function () {
    $this->actingAs(collectionAdmin())
        ->postJson(route('admin.collections.store'), collectionAttributes([
            'slug' => 'invalid-page',
            'route_name' => 'admin.collections.index',
        ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('route_name');

    $this->assertDatabaseCount('collections', 0);
});

it('renders active database collections and keeps draft collections hidden', function () {
    Collection::create(collectionAttributes());
    Collection::create(collectionAttributes([
        'title' => 'Hidden Draft Collection',
        'slug' => 'hidden-draft',
        'status' => 'draft',
        'sort_order' => 2,
    ]));

    $this->get(route('collections.index'))
        ->assertOk()
        ->assertSee('Festive Favourites')
        ->assertSee('Occasion-ready styles for every celebration.')
        ->assertDontSee('Hidden Draft Collection')
        ->assertSee('collections-index-grid', false);
});
