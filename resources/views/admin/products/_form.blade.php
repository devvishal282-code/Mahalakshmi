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
                    <label for="collection_ids">Collections</label>
                    <select id="collection_ids" class="admin-product-collections-select" name="collection_ids[]" multiple data-placeholder="Search and select collections">
                        @foreach ($collections as $collection)
                            <option value="{{ $collection->id }}" @selected(in_array($collection->id, old('collection_ids', $selectedCollectionIds)))>
                                {{ $collection->title }}{{ $collection->status === 'draft' ? ' (Draft)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <small class="admin-product-image-hint">Choose one or more collections. Start typing to search.</small>
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
                    <label for="product_images">Product images <span aria-hidden="true">*</span></label>
                    <div class="admin-product-file-picker">
                        <input
                            id="product_images"
                            name="product_images[]"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            aria-describedby="product-image-hint"
                            multiple
                        >
                        <label class="admin-product-file-picker-button" for="product_images">
                            Add images
                        </label>
                    </div>
                    <small id="product-image-hint" class="admin-product-image-hint">Add up to 10 JPG, PNG, or WebP images, maximum 2 MB each. Select exactly one primary image.</small>
                    <div id="removed-product-images"></div>
                    <div id="product-image-gallery" class="admin-product-image-gallery" aria-live="polite">
                        @foreach ($productImages as $image)
                            <article class="admin-product-image-card" data-existing-image="{{ $image->id }}">
                                <img src="{{ $image->url() }}" alt="{{ $product?->name }} image {{ $loop->iteration }}">
                                <label class="admin-product-primary-choice">
                                    <input type="radio" name="primary_image" value="existing:{{ $image->id }}" required @checked($image->is_primary)>
                                    <span>Primary</span>
                                </label>
                                <button type="button" class="admin-product-image-remove" data-remove-existing-image="{{ $image->id }}" aria-label="Remove image {{ $loop->iteration }}">&times;</button>
                            </article>
                        @endforeach
                        <p id="product-image-gallery-empty" class="admin-product-image-gallery-empty" @if ($productImages->isNotEmpty()) hidden @endif>
                            No images selected yet.
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
