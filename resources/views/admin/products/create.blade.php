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

        @include('admin.products._form', ['isModal' => false])
    </section>
@endsection
