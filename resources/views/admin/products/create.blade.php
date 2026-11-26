@extends('admin.adminLayout')

@section('title', ($product ? 'Edit Product' : 'Add Product').' | Mahalakshmi Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

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

        @include('admin.products._form', ['isModal' => false])
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('admin.products._form-scripts')
    <script>
        $(function () {
            window.initializeProductForm(document.getElementById('product-form'));
        });
    </script>
@endpush
