(function ($, window) {
    if (!$ || !$.fn) {
        throw new Error('jQuery must be loaded before snippetapp.js.');
    }

    if (!window.Swal) {
        throw new Error('SweetAlert2 must be loaded before snippetapp.js.');
    }

    var toast = window.Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toastElement) => {
            $(toastElement).on('mouseenter', window.Swal.stopTimer);
            $(toastElement).on('mouseleave', window.Swal.resumeTimer);
        }
    });

    var showToast = function (icon, title, text) {
        return toast.fire({
            icon: icon,
            title: title || '',
            text: text || ''
        });
    };

    var showAlert = function (icon, title, text, options) {
        options = options || {};

        return window.Swal.fire($.extend({}, {
            confirmButtonColor: '#008f49',
            scrollbarPadding: false
        }, options, {
            title: title || '',
            text: text || '',
            icon: icon
        }));
    };

    window.snippetapp = {
        toast: {
            success: function (title, text) {
                return showToast('success', title, text);
            },
            error: function (title, text) {
                return showToast('error', title, text);
            },
            warning: function (title, text) {
                return showToast('warning', title, text);
            }
        },
        swal: {
            success: function (title, text, options) {
                return showAlert('success', title, text, options);
            },
            warning: function (title, text, options) {
                return showAlert('warning', title, text, options);
            },
            error: function (title, text, options) {
                return showAlert('error', title, text, options);
            },
            question: function (title, text, options) {
                return showAlert('question', title, text, $.extend({
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }, options));
            }
        }
    };
})(window.jQuery, window);
