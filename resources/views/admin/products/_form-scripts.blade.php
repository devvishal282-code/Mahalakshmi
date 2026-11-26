<script>
    window.initializeProductForm = function (formElement) {
        var form = formElement instanceof HTMLElement ? formElement : document.getElementById('product-form');

        if (!form || form.dataset.productFormReady === 'true') {
            return;
        }

        form.dataset.productFormReady = 'true';

        var collectionsSelect = $(form).find('.admin-product-collections-select');
        if (collectionsSelect.length && $.fn.select2) {
            collectionsSelect.select2({
                width: '100%',
                placeholder: collectionsSelect.data('placeholder'),
                closeOnSelect: false,
                dropdownParent: $(form).closest('dialog').length ? $(form).closest('dialog') : $(document.body)
            });
        }

        var input = form.querySelector('#product_images');
        var gallery = form.querySelector('#product-image-gallery');
        var emptyState = form.querySelector('#product-image-gallery-empty');
        var removedInputs = form.querySelector('#removed-product-images');

        if (!input || !gallery || !removedInputs) {
            return;
        }

        var selectedFiles = [];
        var objectUrls = [];

        function syncInputFiles() {
            var transfer = new DataTransfer();
            selectedFiles.forEach(function (file) {
                transfer.items.add(file);
            });
            input.files = transfer.files;
        }

        function selectFirstPrimary() {
            var firstPrimary = gallery.querySelector('input[name="primary_image"]');
            if (firstPrimary) {
                firstPrimary.checked = true;
            }
        }

        function updateEmptyState() {
            var hasImages = gallery.querySelector('.admin-product-image-card') !== null;
            emptyState.hidden = hasImages;
        }

        function renderNewImages(primaryFile) {
            objectUrls.forEach(function (url) {
                URL.revokeObjectURL(url);
            });
            objectUrls = [];
            gallery.querySelectorAll('[data-new-image]').forEach(function (card) {
                card.remove();
            });

            selectedFiles.forEach(function (file, index) {
                var objectUrl = URL.createObjectURL(file);
                objectUrls.push(objectUrl);

                var card = document.createElement('article');
                card.className = 'admin-product-image-card';
                card.dataset.newImage = String(index);
                card.innerHTML =
                    '<img alt="New product image preview">' +
                    '<label class="admin-product-primary-choice">' +
                    '<input type="radio" name="primary_image" value="new:' + index + '" required>' +
                    '<span>Primary</span></label>' +
                    '<button type="button" class="admin-product-image-remove" data-remove-new-image="' + index + '" aria-label="Remove ' + file.name.replace(/"/g, '') + '">&times;</button>' +
                    '<small></small>';
                card.querySelector('img').src = objectUrl;
                card.querySelector('small').textContent = file.name;
                gallery.insertBefore(card, emptyState);

                if (file === primaryFile) {
                    card.querySelector('input[name="primary_image"]').checked = true;
                }
            });

            syncInputFiles();
            updateEmptyState();

            if (!gallery.querySelector('input[name="primary_image"]:checked')) {
                selectFirstPrimary();
            }
        }

        input.addEventListener('change', function () {
            var incomingFiles = Array.from(input.files || []);
            var existingCount = gallery.querySelectorAll('[data-existing-image]').length;

            if (existingCount + selectedFiles.length + incomingFiles.length > 10) {
                snippetapp.toast.warning('Image limit', 'A product may have a maximum of 10 images.');
                syncInputFiles();
                return;
            }

            var currentPrimary = gallery.querySelector('input[name="primary_image"]:checked');
            var primaryFile = currentPrimary?.closest('[data-new-image]')
                ? selectedFiles[Number(currentPrimary.closest('[data-new-image]').dataset.newImage)]
                : null;
            selectedFiles = selectedFiles.concat(incomingFiles);
            renderNewImages(primaryFile);
        });

        gallery.addEventListener('click', function (event) {
            var removeExistingButton = event.target.closest('[data-remove-existing-image]');
            var removeNewButton = event.target.closest('[data-remove-new-image]');

            if (removeExistingButton) {
                var existingCard = removeExistingButton.closest('[data-existing-image]');
                var wasPrimary = existingCard.querySelector('input[name="primary_image"]')?.checked;
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'removed_image_ids[]';
                hiddenInput.value = removeExistingButton.dataset.removeExistingImage;
                removedInputs.append(hiddenInput);
                existingCard.remove();
                updateEmptyState();

                if (wasPrimary) {
                    selectFirstPrimary();
                }
            }

            if (removeNewButton) {
                var index = Number(removeNewButton.dataset.removeNewImage);
                var newCard = removeNewButton.closest('[data-new-image]');
                var wasNewPrimary = newCard.querySelector('input[name="primary_image"]')?.checked;
                var currentPrimary = gallery.querySelector('input[name="primary_image"]:checked');
                var primaryFile = currentPrimary?.closest('[data-new-image]')
                    ? selectedFiles[Number(currentPrimary.closest('[data-new-image]').dataset.newImage)]
                    : null;

                if (wasNewPrimary) {
                    primaryFile = null;
                }

                selectedFiles.splice(index, 1);
                renderNewImages(primaryFile);
            }
        });

        updateEmptyState();
    };
</script>
