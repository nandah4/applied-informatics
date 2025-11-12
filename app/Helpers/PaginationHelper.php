<?php

/**
 * File: Helpers/PaginationHelper.php
 * Description: Helper untuk menangani pagination logic
 */

class PaginationHelper
{
    /**
     * Generate pagination data
     * 
     * @param int $totalRecords - Total semua data
     * @param int $currentPage - Halaman saat ini
     * @param int $perPage - Jumlah data per halaman
     * @return array - Pagination data
     */
    public static function paginate($totalRecords, $currentPage = 1, $perPage = 10)
    {
        // Pastikan values valid
        $currentPage = max(1, (int)$currentPage);
        $perPage = max(1, min(100, (int)$perPage)); // Max 100 per page

        // Hitung total halaman
        $totalPages = ceil($totalRecords / $perPage);

        // Pastikan current page tidak melebihi total pages
        $currentPage = min($currentPage, max(1, $totalPages));

        // Hitung offset untuk query
        $offset = ($currentPage - 1) * $perPage;

        // Generate page numbers untuk display
        $pageNumbers = self::generatePageNumbers($currentPage, $totalPages);

        return [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'offset' => $offset,
            'has_prev' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'prev_page' => max(1, $currentPage - 1),
            'next_page' => min($totalPages, $currentPage + 1),
            'page_numbers' => $pageNumbers,
            'showing_from' => $offset + 1,
            'showing_to' => min($offset + $perPage, $totalRecords)
        ];
    }

    /**
     * Generate page numbers dengan ellipsis (...) untuk total page > 3
     * 
     * @param int $currentPage
     * @param int $totalPages
     * @return array - Array of page numbers dengan 'ellipsis' indicator
     */
    private static function generatePageNumbers($currentPage, $totalPages)
    {
        $pages = [];

        // Jika total pages <= 5, tampilkan semua
        if ($totalPages <= 5) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $pages[] = ['number' => $i, 'is_ellipsis' => false];
            }
            return $pages;
        }

        // Selalu tampilkan halaman pertama
        $pages[] = ['number' => 1, 'is_ellipsis' => false];

        // Tentukan range yang akan ditampilkan
        $start = max(2, $currentPage - 1);
        $end = min($totalPages - 1, $currentPage + 1);

        // Tambahkan ellipsis awal jika perlu
        if ($start > 2) {
            $pages[] = ['number' => '...', 'is_ellipsis' => true];
        }

        // Tambahkan page numbers di tengah
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = ['number' => $i, 'is_ellipsis' => false];
        }

        // Tambahkan ellipsis akhir jika perlu
        if ($end < $totalPages - 1) {
            $pages[] = ['number' => '...', 'is_ellipsis' => true];
        }

        // Selalu tampilkan halaman terakhir
        if ($totalPages > 1) {
            $pages[] = ['number' => $totalPages, 'is_ellipsis' => false];
        }

        return $pages;
    }
}
