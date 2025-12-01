/**
 * File: js/pages/asisten-lab/index.js
 * Deskripsi: JavaScript untuk halaman admin/asisten-lab (Index)
 *
 * Fitur:
 * - Search & filter asisten lab
 * - Filter by status (aktif/tidak aktif)
 * - Pagination control
 * - Delete asisten lab dengan konfirmasi
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
            window.location.href = `${BASE_URL}/admin/asisten-lab`;
        });

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
     * Proses delete asisten lab via AJAX
     *
     * @param {number} id - ID asisten lab yang akan dihapus
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
