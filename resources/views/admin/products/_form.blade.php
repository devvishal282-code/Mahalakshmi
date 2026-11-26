<form
    id="product-form"
    class="admin-product-form{{ ($isModal ?? false) ? ' admin-product-form--modal' : '' }}"
    method="POST"
    enctype="multipart/form-data"
    action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
>
    @csrf
    @if ($product)
        @method('PUT')
    @endif

        <div class="admin-product-form-content">
            <div class="admin-product-form-grid">
                <div class="admin-product-field">
                    <label for="name">Product name <span aria-hidden="true">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name', $product?->name) }}" maxlength="255" required>
                </div>

                <div class="admin-product-field">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id">
                        <option value="">No category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-product-field admin-product-field-full">
                    <label for="product-collection-dropdown">Collections</label>
                    <input type="hidden" name="collection_ids[]" value="">
                    <details id="product-collection-dropdown" class="admin-product-collection-dropdown">
                        <summary id="product-collections-summary">
                            {{ count($selectedCollectionIds) ? count($selectedCollectionIds).' selected' : 'Select collections' }}
                        </summary>
                        <div class="admin-product-collection-options" role="group" aria-labelledby="product-collections-summary">
                            @forelse ($collections as $collection)
                                <label class="admin-product-collection-option">
                                    <input
                                        type="checkbox"
                                        name="collection_ids[]"
                                        value="{{ $collection->id }}"
                                        @checked(in_array($collection->id, $selectedCollectionIds))
                                    >
                                    <span>{{ $collection->title }}</span>
                                    @if ($collection->status === 'draft')
                                        <small>Draft</small>
                                    @endif
                                </label>
                            @empty
                                <p class="admin-product-collection-empty">No collections available.</p>
                            @endforelse
                        </div>
                    </details>
                </div>

                <div class="admin-product-field admin-product-field-full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4">{{ old('description', $product?->description) }}</textarea>
                </div>

                <div class="admin-product-field">
                    <label for="price">Price (INR) <span aria-hidden="true">*</span></label>
                    <input id="price" name="price" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('price', $product?->price) }}" required>
                </div>

                <div class="admin-product-field">
                    <label for="stock_quantity">Stock quantity <span aria-hidden="true">*</span></label>
                    <input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" value="{{ old('stock_quantity', $product?->stock_quantity ?? 0) }}" required>
                </div>

                <div class="admin-product-field admin-product-field-full">
                    <label for="product_image">Product image</label>
                    <div class="admin-product-file-picker">
                        <input
                            id="product_image"
                            name="product_image"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            aria-describedby="product-image-file-name product-image-hint"
                        >
                        <label class="admin-product-file-picker-button" for="product_image">
                            Browse
                        </label>
                        <div
                            id="product-image-file-name"
                            class="admin-product-file-name"
                            data-initial-file-name="{{ $product?->image_path ? basename($product->image_path) : '' }}"
                            aria-live="polite"
                            @unless ($product?->image_path) hidden @endunless
                        >
                            <span class="admin-product-file-new" hidden>new</span>
                            <span class="admin-product-file-name-text">{{ $product?->image_path ? basename($product->image_path) : '' }}</span>
                            <button
                                class="admin-product-file-remove"
                                type="button"
                                aria-label="Remove selected image"
                                title="Remove selected image"
                                hidden
                            >&times;</button>
                        </div>
                    </div>
                    <small id="product-image-hint" class="admin-product-image-hint">JPG, PNG, or WebP. Maximum file size: 2 MB.</small>
                    <div class="admin-product-image-preview">
                        <img
                            id="product-image-preview"
                            src="{{ $imagePreviewUrl ?? '' }}"
                            data-initial-src="{{ $imagePreviewUrl ?? '' }}"
                            alt="{{ $product?->name ? $product->name.' image preview' : 'Selected product image preview' }}"
                            @if (! $imagePreviewUrl) hidden @endif
                        >
                        <p id="product-image-preview-empty" @if ($imagePreviewUrl) hidden @endif>
                            {{ $product?->image_path ? 'The saved image is unavailable for preview.' : 'The selected image preview will appear here.' }}
                        </p>
                    </div>
                </div>

                <fieldset class="admin-product-status-field">
                    <legend>Status <span aria-hidden="true">*</span></legend>
                    <div class="admin-product-status-options">
                        <label for="status-draft">
                            <input id="status-draft" name="status" type="radio" value="draft" required @checked(old('status', $product?->status ?? 'draft') === 'draft')>
                            Draft
                        </label>
                        <label for="status-active">
                            <input id="status-active" name="status" type="radio" value="active" required @checked(old('status', $product?->status) === 'active')>
                            Active
                        </label>
                    </div>
                </fieldset>
            </div>

            <label class="admin-product-toggle" for="show_marketplace">
                <input id="show_marketplace" name="show_marketplace" type="checkbox" role="switch" value="1" @checked(old('show_marketplace', $product?->show_marketplace ?? false))>
                <span class="admin-product-toggle-track" aria-hidden="true">
                    <span class="admin-product-toggle-thumb"></span>
                </span>
                <span>Show this product on the marketplace</span>
            </label>
    </div>

    <div class="admin-product-form-actions">
        @if ($isModal ?? false)
            <button class="admin-product-back-button" type="button" data-product-modal-close>Cancel</button>
        @else
            <a class="admin-product-back-button" href="{{ route('admin.products.index') }}">Cancel</a>
        @endif
        <button class="admin-product-add-button" type="submit">{{ $product ? 'Update Product' : 'Save Product' }}</button>
    </div>
</form>
