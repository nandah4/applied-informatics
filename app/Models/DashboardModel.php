<?php

/**
 * File: Models/DashboardModel.php
 * Deskripsi: Model untuk menangani operasi database terkait dashboard admin
 *
 * View yang digunakan:
 * - v_dashboard_count: View untuk statistik count berbagai entitas
 * - vw_show_publikasi: View publikasi dengan join dosen
 *
 * Fungsi utama:
 * - getDashboardStats(): Ambil statistik dashboard dari v_dashboard_count
 * - getRecentPublikasi(): Ambil 5 publikasi terbaru
 * - getPublikasiByTipe(): Ambil jumlah publikasi per tipe
 */

class DashboardModel
{
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    /**
     * Get statistik dashboard dari view v_dashboard_count
     *
     * @return array - Format: [
     *                     'total_dosen' => int,
     *                     'total_publikasi' => int,
     *                     'total_mitra' => int,
     *                     'total_produk' => int,
     *                     'total_fasilitas' => int,
     *                     'total_keahlian' => int,
     *                     'total_jabatan' => int,
     *                     'total_aktivitas_lab' => int
     *                 ]
     */
    public function getDashboardStats()
    {
        try {
            $query = "SELECT * FROM v_dashboard_count";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil statistik dashboard: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get publikasi terbaru (limit 5)
     * Menggunakan view vw_show_publikasi
     *
     * @param int $limit - Jumlah data yang diambil (default: 5)
     * @return array - Format: ['success' => bool, 'data' => array]
     */
    public function getRecentPublikasi($limit = 5)
    {
        try {
            $query = "SELECT * FROM vw_show_publikasi
                      ORDER BY tahun_publikasi DESC
                      LIMIT :limit";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil publikasi terbaru: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get jumlah publikasi berdasarkan tipe
     * Menghitung publikasi per tipe: Riset, Kekayaan Intelektual, PPM
     *
     * @return array - Format: ['success' => bool, 'data' => ['Riset' => int, ...]]
     */
    public function getPublikasiByTipe()
    {
        try {
            $query = "SELECT
                        tipe_publikasi,
                        COUNT(*) as jumlah
                      FROM trx_publikasi
                      GROUP BY tipe_publikasi
                      ORDER BY tipe_publikasi";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Convert to associative array dengan tipe sebagai key
            $data = [
                'Riset' => 0,
                'Kekayaan Intelektual' => 0,
                'PPM' => 0
            ];

            foreach ($results as $row) {
                $data[$row['tipe_publikasi']] = (int)$row['jumlah'];
            }

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil statistik publikasi: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}
