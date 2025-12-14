/**
 * File: js/pages/contact/index.js
 * Deskripsi: JavaScript untuk halaman admin/contact (Index)
 *
 * Fitur:
 * - Search & filter pesan
 * - Filter by status (Baru/Dibalas)
 * - Pagination control
 * - Delete pesan dengan konfirmasi
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
            window.location.href = `${BASE_URL}/admin/hubungi-kami`;
        });

        function performSearch() {
            const searchValue = $('#searchInput').val().trim();
            const currentUrl = new URL(window.location.href);
            const perPage = currentUrl.searchParams.get('per_page') || '10';
            const status = currentUrl.searchParams.get('status') || '';

            let url = `${BASE_URL}/admin/hubungi-kami?per_page=${perPage}`;
            
            if (searchValue) {
                url += `&search=${encodeURIComponent(searchValue)}`;
            }
            
            if (status) {
                url += `&status=${encodeURIComponent(status)}`;
            }

            window.location.href = url;
        }

        // ========================================
        // Status Filter
        // ========================================

        $('#statusFilter').on('change', function () {
            const status = $(this).val();
            const currentUrl = new URL(window.location.href);
            const perPage = currentUrl.searchParams.get('per_page') || '10';
            const search = currentUrl.searchParams.get('search') || '';

            let url = `${BASE_URL}/admin/hubungi-kami?per_page=${perPage}`;
            
            if (search) {
                url += `&search=${encodeURIComponent(search)}`;
            }
            
            if (status) {
                url += `&status=${encodeURIComponent(status)}`;
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
            const status = currentUrl.searchParams.get('status') || '';

            let url = `${BASE_URL}/admin/hubungi-kami?per_page=${perPage}`;
            
            if (search) {
                url += `&search=${encodeURIComponent(search)}`;
            }
            
            if (status) {
                url += `&status=${encodeURIComponent(status)}`;
            }

            window.location.href = url;
        });
    });

    // ========================================
    // DELETE PESAN
    // ========================================

    /**
     * Proses delete pesan via AJAX
     *
     * @param {number} id - ID pesan yang akan dihapus
     */
    window.confirmDelete = function (id) {
        if (!confirm("Apakah Anda yakin ingin menghapus pesan ini?\n\nTindakan ini tidak dapat dibatalkan.")) {
            return;
        }

        const csrfToken = $('input[name="csrf_token"]').val();
        const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
        deleteBtn.prop("disabled", true);

        jQueryHelpers.makeAjaxRequest({
            url: `${BASE_URL}/admin/hubungi-kami/delete/${id}`,
            method: "POST",
            data: { csrf_token: csrfToken },
            onSuccess: (response) => {
                if (response.success) {
                    jQueryHelpers.showAlert("Pesan berhasil dihapus!", "success", 2000);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    jQueryHelpers.showAlert(response.message || "Gagal menghapus pesan", "danger", 5000);
                    deleteBtn.prop("disabled", false);
                }
            },
            onError: (errorMessage) => {
                jQueryHelpers.showAlert("Terjadi kesalahan sistem.", "danger", 5000);
                console.error("Delete pesan error:", errorMessage);
                deleteBtn.prop("disabled", false);
            },
        });
    };

})();