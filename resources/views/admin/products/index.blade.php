@extends('admin.adminLayout')

@section('title', 'Products | Mahalakshmi Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@section('content')
    <section class="admin-dashboard">
        <div class="admin-dashboard-heading">
            <div>
                <p class="admin-dashboard-eyebrow">PRODUCT MANAGEMENT</p>
                <h1>Products</h1>
                <p class="admin-dashboard-description">View and manage the products in your store.</p>
            </div>
            <button
                class="admin-product-add-button"
                type="button"
                data-product-modal-open
                data-url="{{ route('admin.products.create') }}"
            >Add Product</button>
        </div>

        <div class="admin-product-table-card">
            <div class="admin-product-table-wrapper">
                <table id="products-table" class="admin-product-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Collections</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Image</th>
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

    <dialog id="product-modal" class="admin-product-modal" aria-labelledby="product-modal-title">
        <div class="admin-product-modal-panel">
            <div class="admin-product-modal-header">
                <h2 id="product-modal-title">Product</h2>
                <button class="admin-product-modal-close" type="button" data-product-modal-close aria-label="Close">&times;</button>
            </div>
            <div id="product-modal-body">
                <p class="admin-product-modal-loading">Loading product form...</p>
            </div>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('admin.products._form-scripts')
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
                    data: 'collection_names',
                    name: 'collection_names',
                    orderable: false,
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
                    data: 'image_url',
                    name: 'image_path',
                    orderable: false,
                    searchable: false,
                    render: function (value, type, row) {
                        if (type !== 'display') {
                            return value || '';
                        }

                        if (!value) {
                            return '—';
                        }

                        var imageUrl = new URL(value, window.location.href);

                        if (!['http:', 'https:'].includes(imageUrl.protocol)) {
                            return '—';
                        }

                        var image = document.createElement('img');
                        image.className = 'admin-product-table-image';
                        image.src = imageUrl.href;
                        image.alt = (row.name || 'Product') + ' image';
                        image.loading = 'lazy';

                        return image;
                    }
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


        $(document).ready(function(){
            initProductsTable();

            var productModal = document.getElementById('product-modal');
            var productModalBody = $('#product-modal-body');

            function loadProductForm(url, data) {
                productModalBody.html('<p class="admin-product-modal-loading">Loading product form...</p>');
                productModal.showModal();

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: data || {},
                    dataType: 'json',
                    headers: {
                        Accept: 'application/json'
                    },
                    success: function(response) {
                        $('#product-modal-title').text(response.title);
                        productModalBody.html(response.html);
                        window.initializeProductForm(productModalBody.find('#product-form')[0]);
                    },
                    error: function(xhr) {
                        productModalBody.html('<p class="admin-product-modal-loading">Unable to load the product form.</p>');
                        snippetapp.toast.error(
                            'Error',
                            xhr.responseJSON?.message || 'Unable to load the product form.'
                        );
                    }
                });
            }

            $(document).on('click', '[data-product-modal-open]', function() {
                loadProductForm($(this).data('url'));
            });

            $(document).on('click', '.editProduct', function(event) {
                event.preventDefault();
                loadProductForm(@json(route('admin.products.edit')), {
                    id: $(this).data('id')
                });
            });

            $(document).on('click', '[data-product-modal-close]', function() {
                productModal.close();
            });

            productModal.addEventListener('click', function(event) {
                if (event.target === productModal) {
                    productModal.close();
                }
            });

            $(document).on('submit', '#product-form', function(event) {
                event.preventDefault();

                var form = this;
                var submitButton = $(form).find('button[type="submit"]');
                submitButton.prop('disabled', true);

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    headers: {
                        Accept: 'application/json'
                    },
                    success: function(response) {
                        snippetapp.toast.success('Success', response.message);
                        productModal.close();
                        $('#products-table').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            var validationMessages = Object.values(xhr.responseJSON.errors).flat();
                            snippetapp.toast.warning('Please check the form', validationMessages.join(' '));
                            return;
                        }

                        snippetapp.toast.error(
                            'Error',
                            xhr.responseJSON?.message || 'Unable to save the product.'
                        );
                    },
                    complete: function() {
                        submitButton.prop('disabled', false);
                    }
                });
            });

            $(document).on('click', '.deleteProduct', function(){
                var productId = $(this).attr('data-id');

                snippetapp.swal.question('Are you sure?', 'You want to delete this product.').then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.products.destroy') }}",
                            method: 'DELETE',
                            data:{
                                id: productId,
                                _token: @json(csrf_token())
                            },
                            success: function(response){
                                if(response.success){
                                    snippetapp.toast.success(response.message);
                                    $('#products-table').DataTable().ajax.reload(null, false);
                                } else {
                                    snippetapp.toast.warning(response.message);
                                }
                            },
                            error: function() {
                                snippetapp.toast.error("Something Want Wrong.");
                            }
                        });
                    }
                });

            });
        });
    </script>
@endpush
