/**
 * File: pages/pendaftar/index.js
 * Deskripsi: Script untuk halaman daftar pendaftar rekrutmen
 *
 * Fitur:
 * - Search & filter pendaftar (by status seleksi)
 * - Pagination control
 * - Delete pendaftar dengan konfirmasi
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function () {
    "use strict";

    /** @type {string} Base URL aplikasi */
    const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

    $(document).ready(function () {
        // ========================================
        // Search Functionality
        // ========================================

        /**
         * Event listener untuk pencarian dengan tombol Enter
         * @listens keypress
         */
        $('#searchInput').on('keypress', function (e) {
            if (e.which === 13) {
                performSearch();
            }
        });

        /**
         * Event listener untuk tombol search
         * @listens click
         */
        $('#btnSearch').on('click', function () {
            performSearch();
        });

        /**
         * Event listener untuk tombol clear search
         * Menghapus kata kunci pencarian tapi mempertahankan filter status
         * @listens click
         */
        $('#btnClearSearch').on('click', function () {
            const currentUrl = new URL(window.location.href);
            const statusFilter = currentUrl.searchParams.get('status_seleksi') || 'all';
            window.location.href = `${BASE_URL}/admin/daftar-pendaftar?status_seleksi=${statusFilter}`;
        });

        /**
         * Melakukan pencarian pendaftar berdasarkan kata kunci
         * Mempertahankan filter status dan per_page saat pencarian
         * @function performSearch
         * @returns {void}
         */
        function performSearch() {
            const searchValue = $('#searchInput').val().trim();
            const currentUrl = new URL(window.location.href);
            const perPage = currentUrl.searchParams.get('per_page') || '10';
            const statusFilter = currentUrl.searchParams.get('status_seleksi') || 'all';

            let url = `${BASE_URL}/admin/daftar-pendaftar?per_page=${perPage}&status_seleksi=${statusFilter}`;

            if (searchValue) {
                url += `&search=${encodeURIComponent(searchValue)}`;
            }

            window.location.href = url;
        }

        // ========================================
        // Status Filter Change
        // ========================================

        /**
         * Event listener untuk perubahan filter status seleksi
         * @listens change
         */
        $('#statusFilter').on('change', function () {
            const statusValue = $(this).val();
            const currentUrl = new URL(window.location.href);
            const perPage = currentUrl.searchParams.get('per_page') || '10';
            const search = currentUrl.searchParams.get('search') || '';

            let url = `${BASE_URL}/admin/daftar-pendaftar?per_page=${perPage}&status_seleksi=${statusValue}`;

            if (search) {
                url += `&search=${encodeURIComponent(search)}`;
            }

            window.location.href = url;
        });

        // ========================================
        // Per Page Change
        // ========================================

        /**
         * Event listener untuk perubahan jumlah item per halaman
         * @listens change
         */
        $('#perPageSelect').on('change', function () {
            const perPage = $(this).val();
            const currentUrl = new URL(window.location.href);
            const search = currentUrl.searchParams.get('search') || '';
            const statusFilter = currentUrl.searchParams.get('status_seleksi') || 'all';

            let url = `${BASE_URL}/admin/daftar-pendaftar?per_page=${perPage}&status_seleksi=${statusFilter}`;
            if (search) {
                url += `&search=${encodeURIComponent(search)}`;
            }

            window.location.href = url;
        });
    });

    // ========================================
    // DELETE PENDAFTAR
    // ========================================

    /**
     * Menampilkan konfirmasi dan memproses penghapusan pendaftar
     * Mengirim request DELETE via AJAX dengan CSRF token
     * File CV dan KHS akan otomatis terhapus di server
     * 
     * @function confirmDelete
     * @global
     * @param {number} id - ID pendaftar yang akan dihapus
     * @returns {void}
     * 
     * @example
     * // Dipanggil dari onclick button
     * confirmDelete(123);
     */
    window.confirmDelete = function (id) {
        if (!confirm("Apakah Anda yakin ingin menghapus data pendaftar ini?\n\nFile CV dan KHS juga akan terhapus.")) {
            return;
        }

        const csrfToken = $('input[name="csrf_token"]').val();
        const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
        deleteBtn.prop("disabled", true);

        jQueryHelpers.makeAjaxRequest({
            url: `${BASE_URL}/admin/daftar-pendaftar/delete/${id}`,
            method: "POST",
            data: { csrf_token: csrfToken },
            onSuccess: (response) => {
                if (response.success) {
                    jQueryHelpers.showAlert("Data pendaftar berhasil dihapus!", "success", 2000);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    jQueryHelpers.showAlert(response.message || "Gagal menghapus data pendaftar", "danger", 5000);
                    deleteBtn.prop("disabled", false);
                }
            },
            onError: (errorMessage) => {
                jQueryHelpers.showAlert("Terjadi kesalahan sistem.", "danger", 5000);
                console.error("Delete pendaftar error:", errorMessage);
                deleteBtn.prop("disabled", false);
            },
        });
    };

})();

