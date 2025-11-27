/**
 * File: pages/publikasi_dosen/publikasi_dosen.js
 * Deskripsi: Script untuk halaman publikasi dosen (client view)
 *
 * Fitur:
 * - Search publikasi berdasarkan judul, nama dosen
 * - Filter publikasi berdasarkan tipe (Riset, Kekayaan Intelektual, PPM)
 * - Server-side pagination
 *
 * Dependencies:
 * - jQuery
 */

(function() {
    'use strict';

    // ============================================================
    // MODUL SEARCH & FILTER
    // ============================================================

    const SearchFilterModule = {
        /**
         * Inisialisasi search & filter
         */
        init: function() {
            const $searchInput = $('#searchInput');
            const $btnClear = $('#btnClear');
            const $btnSearch = $('#btnSearch');
            const $filterTipe = $('#filterTipe');

            // Show/hide clear button based on input value
            $searchInput.on('input', function() {
                const searchValue = $(this).val().trim();
                if (searchValue.length > 0) {
                    $btnClear.show();
                } else {
                    $btnClear.hide();
                }
            });

            // Handle clear button
            $btnClear.on('click', function() {
                $searchInput.val('');
                $(this).hide();
                SearchFilterModule.applyFilters();
            });

            // Handle search button click
            $btnSearch.on('click', function() {
                SearchFilterModule.applyFilters();
            });

            // Handle filter tipe publikasi change
            $filterTipe.on('change', function() {
                SearchFilterModule.applyFilters();
            });

            // Handle Enter key pada search input
            $searchInput.on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    SearchFilterModule.applyFilters();
                }
            });
        },

        /**
         * Apply search & filter dengan redirect ke URL baru
         */
        applyFilters: function() {
            const searchValue = $('#searchInput').val().trim();
            const tipeValue = $('#filterTipe').val();

            // Build URL dengan query parameters
            const baseUrl = window.location.pathname;
            const params = new URLSearchParams();

            // Add search parameter jika ada
            if (searchValue) {
                params.append('search', searchValue);
            }

            // Add tipe_publikasi parameter jika ada
            if (tipeValue) {
                params.append('tipe_publikasi', tipeValue);
            }

            // Reset ke page 1 saat apply filter
            params.append('page', '1');

            // Redirect ke URL baru
            const newUrl = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
            window.location.href = newUrl;
        },

        /**
         * Get current URL parameters
         */
        getUrlParams: function() {
            const params = new URLSearchParams(window.location.search);
            return {
                search: params.get('search') || '',
                tipe_publikasi: params.get('tipe_publikasi') || '',
                page: params.get('page') || '1'
            };
        }
    };

    // ============================================================
    // INISIALISASI
    // ============================================================

    /**
     * Jalankan semua modul saat document ready
     */
    $(document).ready(function() {
        // Inisialisasi search & filter
        SearchFilterModule.init();

        // Initialize feather icons jika ada
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

})();
