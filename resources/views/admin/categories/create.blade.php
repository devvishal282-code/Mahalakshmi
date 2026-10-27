@extends('admin.adminLayout')

@section('title', ($category ? 'Edit Category' : 'Add Category').' | Mahalakshmi Admin')

@section('content')
    <section class="admin-dashboard">
        <div class="admin-dashboard-heading">
            <div>
                <p class="admin-dashboard-eyebrow">CATEGORY MANAGEMENT</p>
                <h1>{{ $category ? 'Edit Category' : 'Add Category' }}</h1>
                <p class="admin-dashboard-description">
                    {{ $category ? 'Update the category details below.' : 'Enter the category details below to add it to your store.' }}
                </p>
            </div>
            <a class="admin-product-back-button" href="{{ route('admin.categories.index') }}">Back to Categories</a>
        </div>

        <form class="admin-product-form" method="POST" action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @if ($category)
                @method('PUT')
            @endif

            <div class="admin-product-form-grid">
                <div class="admin-product-field">
                    <label for="name">Category name <span aria-hidden="true">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name', $category?->name) }}" maxlength="255" required autofocus>
                    @error('name')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <fieldset class="admin-product-status-field">
                    <legend>Status <span aria-hidden="true">*</span></legend>
                    <div class="admin-product-status-options">
                        <label for="status-active">
                            <input id="status-active" name="status" type="radio" value="active" required @checked(old('status', $category?->status ?? 'active') === 'active')>
                            Active
                        </label>
                        <label for="status-draft">
                            <input id="status-draft" name="status" type="radio" value="draft" required @checked(old('status', $category?->status) === 'draft')>
                            Draft
                        </label>
                    </div>
                    @error('status')
                        <span class="admin-product-field-error">{{ $message }}</span>
                    @enderror
                </fieldset>
            </div>

            <div class="admin-product-form-actions">
                <a class="admin-product-back-button" href="{{ route('admin.categories.index') }}">Cancel</a>
                <button class="admin-product-add-button" type="submit">{{ $category ? 'Update Category' : 'Save Category' }}</button>
            </div>
        </form>
    </section>
@endsection
