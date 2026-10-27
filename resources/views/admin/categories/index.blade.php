@extends('admin.adminLayout')

@section('title', 'Categories | Mahalakshmi Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
@endpush

@section('content')
    <section class="admin-dashboard">
        <div class="admin-dashboard-heading">
            <div>
                <p class="admin-dashboard-eyebrow">CATEGORY MANAGEMENT</p>
                <h1>Categories</h1>
                <p class="admin-dashboard-description">View and manage the categories in your store.</p>
            </div>
            <a class="admin-product-add-button" href="{{ route('admin.categories.create') }}">Add Category</a>
        </div>

        <div class="admin-product-table-card">
            <div class="admin-product-table-wrapper">
                <table id="categories-table" class="admin-product-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Products</th>
                            <th>Created</th>
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
        function initCategoriesTable() {
            $('#categories-table').DataTable({
                retrieve: true,
                scrollX: false,
                scrollY: false,
                serverSide: true,
                processing: true,
                paging: true,
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                ajax: {
                    url: @json(route('admin.categories.data')),
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: 'products_count',
                        name: 'products_count'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: $.fn.dataTable.render.text()
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
                    zeroRecords: 'No matching categories found.',
                    loadingRecords: 'Please wait, loading categories...',
                    processing: 'Loading categories...'
                }
            });
        }

        $(document).ready(initCategoriesTable);
    </script>
@endpush
