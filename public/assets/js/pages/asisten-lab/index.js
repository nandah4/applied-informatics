/**
 * File: pages/asisten-lab/index.js
 * Deskripsi: Script untuk halaman daftar asisten lab
 *
 * Fitur:
 * - Search & filter asisten lab (by status aktif)
 * - Pagination control
 * - Delete asisten lab dengan konfirmasi
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
            const statusFilter = currentUrl.searchParams.get('status_aktif') || 'all';
            const perPage = currentUrl.searchParams.get('per_page') || '10';
            window.location.href = `${BASE_URL}/admin/asisten-lab?per_page=${perPage}&status_aktif=${statusFilter}`;
        });

        /**
         * Melakukan pencarian asisten lab berdasarkan kata kunci
         * Mempertahankan filter status dan per_page saat pencarian
         * @function performSearch
         * @returns {void}
         */
        function performSearch() {
            const searchValue = $('#searchInput').val().trim();
            const currentUrl = new URL(window.location.href);
            const perPage = currentUrl.searchParams.get('per_page') || '10';
            const statusFilter = currentUrl.searchParams.get('status_aktif') || 'all';

            let url = `${BASE_URL}/admin/asisten-lab?per_page=${perPage}&status_aktif=${statusFilter}`;

            if (searchValue) {
                url += `&search=${encodeURIComponent(searchValue)}`;
            }

            window.location.href = url;
        }

        // ========================================
        // Status Filter Change
        // ========================================

        /**
         * Event listener untuk perubahan filter status
         * @listens change
         */
        $('#statusFilter').on('change', function () {
            const statusValue = $(this).val();
            const currentUrl = new URL(window.location.href);
            const perPage = currentUrl.searchParams.get('per_page') || '10';
            const search = currentUrl.searchParams.get('search') || '';

            let url = `${BASE_URL}/admin/asisten-lab?per_page=${perPage}&status_aktif=${statusValue}`;

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
            const statusFilter = currentUrl.searchParams.get('status_aktif') || 'all';

            let url = `${BASE_URL}/admin/asisten-lab?per_page=${perPage}&status_aktif=${statusFilter}`;

            if (search) {
                url += `&search=${encodeURIComponent(search)}`;
            }

            window.location.href = url;
        });
    });

    // ========================================
    // DELETE ASISTEN LAB
    // ========================================

    /**
     * Menampilkan konfirmasi dan memproses penghapusan asisten lab
     * Mengirim request DELETE via AJAX dengan CSRF token
     * 
     * @function confirmDelete
     * @global
     * @param {number} id - ID asisten lab yang akan dihapus
     * @returns {void}
     * 
     * @example
     * Dipanggil dari onclick button
     * confirmDelete(123);
     */
    window.confirmDelete = function (id) {
        if (!confirm("Apakah Anda yakin ingin menghapus data asisten lab ini?\n\nData yang sudah dihapus tidak dapat dikembalikan.")) {
            return;
        }

        const csrfToken = $('input[name="csrf_token"]').val();
        const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
        deleteBtn.prop("disabled", true);

        jQueryHelpers.makeAjaxRequest({
            url: `${BASE_URL}/admin/asisten-lab/delete/${id}`,
            method: "POST",
            data: { csrf_token: csrfToken },
            onSuccess: (response) => {
                if (response.success) {
                    jQueryHelpers.showAlert("Data asisten lab berhasil dihapus!", "success", 2000);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    jQueryHelpers.showAlert(response.message || "Gagal menghapus data asisten lab", "danger", 5000);
                    deleteBtn.prop("disabled", false);
                }
            },
            onError: (errorMessage) => {
                jQueryHelpers.showAlert("Terjadi kesalahan sistem.", "danger", 5000);
                console.error("Delete asisten lab error:", errorMessage);
                deleteBtn.prop("disabled", false);
            },
        });
    };

})();

