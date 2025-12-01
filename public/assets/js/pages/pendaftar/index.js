/**
 * File: js/pages/pendaftar/index.js
 * Deskripsi: JavaScript untuk halaman admin/daftar-pendaftar (Index)
 *
 * Fitur:
 * - Search & filter pendaftar
 * - Pagination control
 * - Delete pendaftar dengan konfirmasi
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - Bootstrap
 */

(function () {
    "use strict";

    const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

    $(document).ready(function () {
        // ========================================
        // Search Functionality
        // ========================================

        // Enter key di search input
        $('#searchInput').on('keypress', function (e) {
            if (e.which === 13) {
                performSearch();
            }
        });

        // Click search button
        $('#btnSearch').on('click', function () {
            performSearch();
        });

        // Clear search button
        $('#btnClearSearch').on('click', function () {
            window.location.href = `${BASE_URL}/admin/daftar-pendaftar`;
        });

        function performSearch() {
            const searchValue = $('#searchInput').val().trim();
            const currentUrl = new URL(window.location.href);
            const perPage = currentUrl.searchParams.get('per_page') || '10';

            if (searchValue) {
                window.location.href = `${BASE_URL}/admin/daftar-pendaftar?search=${encodeURIComponent(searchValue)}&per_page=${perPage}`;
            } else {
                window.location.href = `${BASE_URL}/admin/daftar-pendaftar?per_page=${perPage}`;
            }
        }

        // ========================================
        // Per Page Change
        // ========================================

        $('#perPageSelect').on('change', function () {
            const perPage = $(this).val();
            const currentUrl = new URL(window.location.href);
            const search = currentUrl.searchParams.get('search') || '';

            let url = `${BASE_URL}/admin/daftar-pendaftar?per_page=${perPage}`;
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
     * Proses delete pendaftar via AJAX
     *
     * @param {number} id - ID pendaftar yang akan dihapus
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
