@extends('admin.adminLayout')

@section('title', 'Collections | Mahalakshmi Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
@endpush

@section('content')
    <section class="admin-dashboard">
        <div class="admin-dashboard-heading">
            <div>
                <p class="admin-dashboard-eyebrow">COLLECTION MANAGEMENT</p>
                <h1>Collections</h1>
                <p class="admin-dashboard-description">Manage collection cards shown on the storefront.</p>
            </div>
            <button
                class="admin-product-add-button"
                type="button"
                data-collection-modal-open
                data-url="{{ route('admin.collections.create') }}"
            >Add Collection</button>
        </div>

        <p id="collection-feedback" class="admin-product-success" role="status" hidden></p>

        <div class="admin-product-table-card">
            <div class="admin-product-table-wrapper">
                <table id="collections-table" class="admin-product-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Collection</th>
                            <th>Slug</th>
                            <th>Destination</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>

    <dialog id="collection-modal" class="admin-product-modal" aria-labelledby="collection-modal-title">
        <div class="admin-product-modal-panel">
            <div class="admin-product-modal-header">
                <h2 id="collection-modal-title">Collection</h2>
                <button class="admin-product-modal-close" type="button" data-collection-modal-close aria-label="Close">&times;</button>
            </div>
            <div id="product-modal-body">
                <div class="admin-collection-loader" role="status">
                    <span class="admin-collection-spinner" aria-hidden="true"></span>
                    <span>Loading collection form...</span>
                </div>
            </div>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            const collectionsTable = $('#collections-table').DataTable({
                retrieve: true,
                serverSide: true,
                processing: true,
                paging: true,
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                ajax: {
                    url: @json(route('admin.collections.data')),
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'image_url',
                        name: 'image_url',
                        orderable: false,
                        searchable: false,
                        render: function (value, type, row) {
                            if (type !== 'display' || !value) {
                                return value || '';
                            }

                            try {
                                const imageUrl = new URL(value, window.location.href);

                                if (!['http:', 'https:'].includes(imageUrl.protocol)) {
                                    return '—';
                                }

                                const image = document.createElement('img');
                                image.className = 'admin-product-table-image';
                                image.src = imageUrl.href;
                                image.alt = (row.title || 'Collection') + ' image';
                                image.loading = 'lazy';

                                return image.outerHTML;
                            } catch {
                                return '—';
                            }
                        }
                    },
                    { data: 'title', name: 'title', render: $.fn.dataTable.render.text() },
                    { data: 'slug', name: 'slug', render: $.fn.dataTable.render.text() },
                    { data: 'route_name', name: 'route_name', render: $.fn.dataTable.render.text() },
                    { data: 'status', name: 'status', render: $.fn.dataTable.render.text() },
                    { data: 'sort_order', name: 'sort_order' },
                    { data: 'updated_at', name: 'updated_at', render: $.fn.dataTable.render.text() },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [],
                language: {
                    emptyTable: 'No collections found',
                    zeroRecords: 'No matching collections found.',
                    loadingRecords: 'Please wait, loading collections...',
                    processing: 'Loading collections...'
                }
            });

            const collectionModal = document.getElementById('collection-modal');
            const collectionModalBody = $('#product-modal-body');
            const feedback = document.getElementById('collection-feedback');
            let collectionFormRequest = null;

            function collectionLoader(message) {
                return '<div class="admin-collection-loader" role="status"><span class="admin-collection-spinner" aria-hidden="true"></span><span>' + message + '</span></div>';
            }

            function loadCollectionForm(url) {
                collectionFormRequest?.abort();
                collectionModalBody.html(collectionLoader('Loading collection form...'));
                collectionModalBody.attr('aria-busy', 'true');

                if (!collectionModal.open) {
                    collectionModal.showModal();
                }

                collectionFormRequest = $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    headers: { Accept: 'application/json' },
                    success: function (response) {
                        collectionFormRequest = null;
                        document.getElementById('collection-modal-title').textContent = response.title;
                        collectionModalBody.html(response.html);
                        collectionModalBody.attr('aria-busy', 'false');
                    },
                    error: function (xhr, status) {
                        collectionFormRequest = null;

                        if (status !== 'abort') {
                            collectionModalBody.html('<p class="admin-product-modal-loading" role="alert">Unable to load the collection form.</p>');
                            collectionModalBody.attr('aria-busy', 'false');
                        }
                    }
                });
            }

            document.addEventListener('click', function (event) {
                const openButton = event.target.closest('[data-collection-modal-open]');
                const editButton = event.target.closest('[data-url].editCollection');
                const closeButton = event.target.closest('[data-collection-modal-close]');

                if (openButton || editButton) {
                    loadCollectionForm((openButton || editButton).dataset.url);
                }

                if (closeButton) {
                    collectionModal.close();
                }
            });

            collectionModal.addEventListener('click', function (event) {
                if (event.target === collectionModal) {
                    collectionModal.close();
                }
            });

            collectionModal.addEventListener('close', function () {
                collectionFormRequest?.abort();
                collectionFormRequest = null;
                collectionModalBody.html(collectionLoader('Loading collection form...'));
                collectionModalBody.attr('aria-busy', 'true');
            });

            document.addEventListener('submit', function (event) {
                if (!event.target.matches('#collection-form')) {
                    return;
                }

                event.preventDefault();

                const form = event.target;
                const submitButton = form.querySelector('button[type="submit"]');
                const originalSubmitLabel = submitButton.textContent.trim();
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="admin-collection-spinner" aria-hidden="true"></span> Saving...';

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    headers: { Accept: 'application/json' },
                    success: function (response) {
                        collectionModal.close();
                        collectionsTable.ajax.reload(null, false);
                        feedback.textContent = response.message;
                        feedback.hidden = false;
                    },
                    error: function (xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        const message = Object.values(errors).flat().join(' ') || xhr.responseJSON?.message || 'Unable to save the collection.';
                        const errorContainer = form.querySelector('[data-collection-form-errors]');

                        if (errorContainer) {
                            errorContainer.textContent = message;
                        }
                    },
                    complete: function () {
                        submitButton.disabled = false;
                        submitButton.textContent = originalSubmitLabel;
                    }
                });
            });
        });
    </script>
@endpush