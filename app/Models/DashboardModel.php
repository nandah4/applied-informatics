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

    public function __construct()
    {
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
            $query = "SELECT * FROM vw_dashboard_count";
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
    public function getRecentPublikasi($limit = 3)
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
     * Get publikasi terbaru (limit 5)
     * Menggunakan view vw_show_publikasi
     *
     * @param int $limit - Jumlah data yang diambil (default: 5)
     * @return array - Format: ['success' => bool, 'data' => array]
     */
    public function getRecentAktivitasLab($limit = 3)
    {
        try {
            $query = "SELECT
                        judul_aktivitas,
                        tanggal_kegiatan
                    FROM trx_aktivitas_lab
                    ORDER BY tanggal_kegiatan DESC, created_at DESC
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
                'message' => 'Gagal mengambil aktivitas lab terbaru: ' . $e->getMessage(),
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
                'PPM' => 0,
                'Publikasi' => 0
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


    /**
     * Get profil statistik untuk home
     *
     * @return array - Format: [
     *                     'total_dosen' => int,
     *                     'total_publikasi' => int,
     *                     'total_mitra' => int,
     *                 ]
     */
    public function getHomeStatistic()
    {
        try {
            $query = "SELECT
                        (SELECT COUNT(*) FROM mst_dosen WHERE status_aktif = TRUE) AS total_anggota,
                        (SELECT COUNT(*) FROM trx_publikasi) AS total_publikasi,
                        (SELECT COUNT(*) FROM mst_mitra) AS total_mitra;";

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
                'message' => 'Gagal mengambil statistik lab terbaru: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get statistik rekrutmen
     *
     * @return array
     */
    public function getRecruitmentStats()
    {
        try {
            $query = "SELECT
                        COUNT(*) as total_recruitment,
                        COUNT(*) FILTER (WHERE status = 'buka') as recruitment_aktif,
                        COUNT(*) FILTER (WHERE status = 'tutup') as recruitment_tutup,
                        (SELECT COUNT(*) FROM trx_pendaftar) as total_pendaftar,
                        (SELECT COUNT(*) FROM trx_pendaftar WHERE status_seleksi = 'Diterima') as pendaftar_diterima,
                        (SELECT COUNT(*) FROM trx_pendaftar WHERE status_seleksi = 'Ditolak') as pendaftar_ditolak,
                        (SELECT COUNT(*) FROM trx_pendaftar WHERE status_seleksi = 'Pending') as pendaftar_menunggu
                      FROM trx_rekrutmen";

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
                'message' => 'Gagal mengambil statistik rekrutmen: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get statistik asisten lab
     *
     * @return array
     */
    public function getAsistenLabStats()
    {
        try {
            $query = "SELECT
                        COUNT(*) as total_asisten,
                        COUNT(*) FILTER (WHERE status_aktif = TRUE) as asisten_aktif,
                        COUNT(*) FILTER (WHERE status_aktif = FALSE) as asisten_tidak_aktif
                      FROM mst_mahasiswa";

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
                'message' => 'Gagal mengambil statistik asisten lab: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get rekrutmen yang sedang aktif
     *
     * @param int $limit
     * @return array
     */
    public function getActiveRecruitment($limit = 5)
    {
        try {
            $query = "SELECT
                        id,
                        judul,
                        tanggal_buka,
                        tanggal_tutup
                      FROM trx_rekrutmen
                      WHERE status = 'buka'
                      ORDER BY tanggal_tutup ASC
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
                'message' => 'Gagal mengambil rekrutmen aktif: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get pendaftar terbaru
     *
     * @param int $limit
     * @return array
     */
    public function getRecentPendaftar($limit = 5)
    {
        try {
            $query = "SELECT
                        p.id,
                        p.nama,
                        p.email,
                        p.nim,
                        p.status_seleksi,
                        p.created_at,
                        r.judul as rekrutmen_judul
                      FROM trx_pendaftar p
                      LEFT JOIN trx_rekrutmen r ON p.rekrutmen_id = r.id
                      ORDER BY p.created_at DESC
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
                'message' => 'Gagal mengambil pendaftar terbaru: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}
