<form
    id="collection-form"
    class="admin-product-form admin-product-form--modal"
    method="POST"
    action="{{ $collection ? route('admin.collections.update', $collection) : route('admin.collections.store') }}"
>
    @csrf
    @if ($collection)
        @method('PUT')
    @endif

    <div class="admin-product-form-content">
        <p data-collection-form-errors class="admin-product-field-error" role="alert" aria-live="assertive"></p>

        <div class="admin-product-form-grid">
            <div class="admin-product-field">
                <label for="collection-title">Title <span aria-hidden="true">*</span></label>
                <input id="collection-title" name="title" type="text" value="{{ $collection?->title }}" maxlength="255" required>
            </div>

            <div class="admin-product-field">
                <label for="collection-slug">Slug <span aria-hidden="true">*</span></label>
                <input id="collection-slug" name="slug" type="text" value="{{ $collection?->slug }}" maxlength="255" pattern="[a-z0-9]+(-[a-z0-9]+)*" required>
            </div>

            <div class="admin-product-field admin-product-field-full">
                <label for="collection-description">Description <span aria-hidden="true">*</span></label>
                <textarea id="collection-description" name="description" rows="3" maxlength="2000" required>{{ $collection?->description }}</textarea>
            </div>

            <div class="admin-product-field admin-product-field-full">
                <label for="collection_image">Collection image <span aria-hidden="true">*</span></label>
                <div class="admin-collection-image-picker">
                    <div class="admin-product-file-picker">
                        <input
                            id="collection_image"
                            name="collection_image"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            data-admin-image-upload
                            data-preview-target="collection-image-preview"
                            data-empty-preview-target="collection-image-preview-empty"
                            data-file-name-target="collection-image-file-name"
                            aria-describedby="collection-image-hint"
                            @required(! $collection)
                        >
                        <label class="admin-product-file-picker-button" for="collection_image">Browse</label>
                        <div
                            id="collection-image-file-name"
                            class="admin-product-file-name"
                            data-initial-file-name="{{ $collection?->image_path ? 'Current image' : '' }}"
                            aria-live="polite"
                            @unless ($collection?->image_path) hidden @endunless
                        >
                            <span class="admin-product-file-new" hidden>new</span>
                            <span class="admin-product-file-name-text">{{ $collection?->image_path ? 'Current image' : '' }}</span>
                            <button
                                class="admin-product-file-remove"
                                type="button"
                                data-file-input="collection_image"
                                aria-label="Remove selected image"
                                title="Remove selected image"
                                hidden
                            >&times;</button>
                        </div>
                        <small id="collection-image-hint" class="admin-product-image-hint">JPG, PNG, or WebP. Maximum file size: 2 MB.</small>
                    </div>

                    <div class="admin-product-image-preview">
                        <img
                            id="collection-image-preview"
                            src="{{ $imagePreviewUrl ?? '' }}"
                            data-initial-src="{{ $imagePreviewUrl ?? '' }}"
                            alt="{{ $collection?->image_alt ?: 'Selected collection image preview' }}"
                            @if (! $imagePreviewUrl) hidden @endif
                        >
                        <p id="collection-image-preview-empty" @if ($imagePreviewUrl) hidden @endif>
                            {{ $collection?->image_path ? 'The saved image is unavailable for preview.' : 'The selected image preview will appear here.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="admin-product-field admin-product-field-full">
                <label for="collection-image-alt">Image alt text</label>
                <input id="collection-image-alt" name="image_alt" type="text" value="{{ $collection?->image_alt }}" maxlength="255">
            </div>

            <div class="admin-product-field">
                <label for="collection-route-name">Destination page <span aria-hidden="true">*</span></label>
                <select id="collection-route-name" name="route_name" required>
                    <option value="">Select a page</option>
                    @foreach ($routeOptions as $routeName => $routeLabel)
                        <option value="{{ $routeName }}" @selected(old('route_name', $collection?->route_name) === $routeName)>{{ $routeLabel }}</option>
                    @endforeach
                </select>
            </div>

            <div class="admin-product-field">
                <label for="collection-status">Status <span aria-hidden="true">*</span></label>
                <select id="collection-status" name="status" required>
                    <option value="active" @selected(old('status', $collection?->status ?? 'active') === 'active')>Active</option>
                    <option value="draft" @selected(old('status', $collection?->status) === 'draft')>Draft</option>
                </select>
            </div>

            <div class="admin-product-field">
                <label for="collection-sort-order">Display order <span aria-hidden="true">*</span></label>
                <input id="collection-sort-order" name="sort_order" type="number" min="0" max="65535" step="1" value="{{ old('sort_order', $collection?->sort_order ?? 0) }}" required>
            </div>
        </div>
    </div>

    <div class="admin-product-form-actions">
        <button class="admin-product-back-button" type="button" data-collection-modal-close>Cancel</button>
        <button class="admin-product-add-button" type="submit">{{ $collection ? 'Update Collection' : 'Save Collection' }}</button>
    </div>
</form>