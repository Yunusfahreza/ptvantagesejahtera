/* Utilitas kecil yang dipakai beberapa halaman. JS vanilla, tanpa build step. */

(function () {
    'use strict';

    /** Token CSRF dari meta tag, dipakai semua request AJAX. */
    window.csrfToken = function () {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    };

    /**
     * Konfirmasi sebelum submit form yang menghapus data.
     * Dipasang lewat atribut data-konfirmasi pada elemen form.
     */
    document.addEventListener('submit', function (ev) {
        var form = ev.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        var pesan = form.getAttribute('data-konfirmasi');
        if (pesan && !window.confirm(pesan)) {
            ev.preventDefault();
        }
    });

    /** Tampilkan nama file yang dipilih di input file. */
    document.addEventListener('change', function (ev) {
        var input = ev.target;
        if (!(input instanceof HTMLInputElement) || input.type !== 'file') {
            return;
        }

        var target = input.parentElement
            ? input.parentElement.querySelector('[data-nama-file]')
            : null;

        if (target) {
            target.textContent = input.files && input.files.length
                ? input.files[0].name
                : '';
        }
    });
}());
