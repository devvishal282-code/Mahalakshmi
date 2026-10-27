@extends('admin.adminLayout')

@section('title', 'Products | Mahalakshmi Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
@endpush

@section('content')
    <section class="admin-dashboard">
        <div class="admin-dashboard-heading">
            <div>
                <p class="admin-dashboard-eyebrow">PRODUCT MANAGEMENT</p>
                <h1>Products</h1>
                <p class="admin-dashboard-description">View and manage the products in your store.</p>
            </div>
            <a class="admin-product-add-button" href="{{ route('admin.products.create') }}">Add Product</a>
        </div>

        <div class="admin-product-table-card">
            <div class="admin-product-table-wrapper">
                <table id="products-table" class="admin-product-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Image Path</th>
                            <th>Status</th>
                            <th>Marketplace</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script>
        
    function initProductsTable() {
        $('#products-table').DataTable({
            retrieve: true,
            scrollX: false,
            scrollY: false,
            serverSide: true,
            processing: true,
            paging: true,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,

            ajax: {
                url: @json(route('admin.products.data')),
                type: 'GET'
            },

            columns: [
                {
                    data: 'name',
                    name: 'name',
                    render: $.fn.dataTable.render.text()
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    orderable: true,
                    searchable: true,
                    render: $.fn.dataTable.render.text()
                },
                {
                    data: 'description',
                    name: 'description',
                    defaultContent: '',
                    render: $.fn.dataTable.render.text()
                },
                {
                    data: 'price',
                    name: 'price',
                    render: function (value, type) {
                        if (type === 'display') {
                            return `₹${Number(value).toFixed(2)}`;
                        }

                        return value;
                    }
                },
                {
                    data: 'stock_quantity',
                    name: 'stock_quantity'
                },
                {
                    data: 'image_path',
                    name: 'image_path',
                    defaultContent: '',
                    render: $.fn.dataTable.render.text()
                },
                {
                    data: 'status',
                    name: 'status',
                    render: $.fn.dataTable.render.text()
                },
                {
                    data: 'show_marketplace',
                    name: 'show_marketplace',
                    render: function (value, type) {
                        if (type !== 'display') {
                            return value;
                        }

                        return Number(value) === 1 ? 'Yes' : 'No';
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (value) {
                        return value;
                    }
                }
            ],

            order: [],

            language: {
                emptyTable: 'No data found',
                zeroRecords: 'No matching products found.',
                loadingRecords: 'Please wait, loading products...',
                processing: 'Loading products...'
            }
        });
    }


        $(document).ready(initProductsTable);
    </script>
@endpush
