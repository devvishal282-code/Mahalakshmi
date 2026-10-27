@extends('admin.adminLayout')

@section('title', ($product ? 'Edit Product' : 'Add Product').' | Mahalakshmi Admin')

@section('content')
    <section class="admin-dashboard">
        <div class="admin-dashboard-heading">
            <div>
                <p class="admin-dashboard-eyebrow">PRODUCT MANAGEMENT</p>
                <h1>{{ $product ? 'Edit Product' : 'Add Product' }}</h1>
                <p class="admin-dashboard-description">
                    {{ $product ? 'Update the product details below.' : 'Enter the product details below to add it to your catalog.' }}
                </p>
            </div>
            <a class="admin-product-back-button" href="{{ route('admin.products.index') }}">Back to Products</a>
        </div>

        <form class="admin-product-form" method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}">
            @csrf
            @if ($product)
                @method('PUT')
            @endif

            <div class="admin-product-form-grid">   
                <div class="admin-product-field">
                    <label for="name">Product name <span aria-hidden="true">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name', $product?->name) }}" maxlength="255" required>
                    @error('name')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
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
                    @error('category_id')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-product-field admin-product-field-full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4">{{ old('description', $product?->description) }}</textarea>
                    @error('description')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="price">Price (INR) <span aria-hidden="true">*</span></label>
                    <input id="price" name="price" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('price', $product?->price) }}" required>
                    @error('price')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="stock_quantity">Stock quantity <span aria-hidden="true">*</span></label>
                    <input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" value="{{ old('stock_quantity', $product?->stock_quantity ?? 0) }}" required>
                    @error('stock_quantity')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="image_path">Image path</label>
                    <input id="image_path" name="image_path" type="text" maxlength="255" value="{{ old('image_path', $product?->image_path) }}" placeholder="Optional image URL or path">
                    @error('image_path')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
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
                    @error('status')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
                </fieldset>
            </div>

            <label class="admin-product-toggle" for="show_marketplace">
                <input id="show_marketplace" name="show_marketplace" type="checkbox" role="switch" value="1" @checked(old('show_marketplace', $product?->show_marketplace ?? false))>
                <span class="admin-product-toggle-track" aria-hidden="true">
                    <span class="admin-product-toggle-thumb"></span>
                </span>
                <span>Show this product on the marketplace</span>
            </label>
            @error('show_marketplace')
                <span class="admin-product-field-error">{{ $message }}</span>
            @enderror

            <div class="admin-product-form-actions">
                <a class="admin-product-back-button" href="{{ route('admin.products.index') }}">Cancel</a>
                <button class="admin-product-add-button" type="submit">{{ $product ? 'Update Product' : 'Save Product' }}</button>
            </div>
        </form>
    </section>
@endsection
