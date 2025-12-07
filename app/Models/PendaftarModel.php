<?php

/**
 * File: Models/PendaftarModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data pendaftar
 *
 * View yang digunakan:
 * - vw_show_pendaftar: View untuk list pendaftar dengan informasi rekrutmen
 */

class PendaftarModel
{
    protected $table_name = "trx_pendaftar";
    protected $view_name = "vw_show_pendaftar";

    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Get all pendaftar dengan search dan pagination
     *
     * @param array $params - Parameter search dan pagination
     *   - search: string (cari di nama, nim, email)
     *   - limit: int
     *   - offset: int
     * @return array
     */
    public function getAllWithSearchAndFilter($params = [])
    {
        try {
            $search = $params['search'] ?? '';
            $limit = $params['limit'] ?? 10;
            $offset = $params['offset'] ?? 0;

            // Build WHERE clause
            $whereClauses = [];
            $bindParams = [];

            // Search by nama, nim, atau email
            if (!empty($search)) {
                $whereClauses[] = "(nama ILIKE :search OR nim ILIKE :search OR email ILIKE :search)";
                $bindParams[':search'] = "%{$search}%";
            }

            $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

            // Count total records
            $countQuery = "SELECT COUNT(*) as total FROM {$this->view_name} {$whereSQL}";
            $countStmt = $this->db->prepare($countQuery);
            foreach ($bindParams as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Get data dengan pagination
            $query = "
                SELECT
                    id,
                    rekrutmen_id,
                    judul_rekrutmen,
                    nim,
                    nama,
                    email,
                    semester,
                    status_seleksi,
                    created_at
                FROM {$this->view_name}
                {$whereSQL}
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset
            ";

            $stmt = $this->db->prepare($query);

            // Bind search params
            foreach ($bindParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            // Bind pagination params
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $result,
                'total' => (int)$totalRecords
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ];
        }
    }

    /**
     * Get pendaftar by ID
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        try {
            $query = "SELECT * FROM {$this->view_name} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Data pendaftar tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update status seleksi pendaftar
     * Jika status = "Diterima", call sp_terima_anggota (otomatis masuk mst_mahasiswa)
     * Jika status = "Ditolak", call sp_update_status_seleksi dengan deskripsi
     *
     * @param int $pendaftar_id
     * @param string $status_baru - "Diterima" atau "Ditolak"
     * @param string|null $deskripsi - Feedback untuk penolakan (only for Ditolak)
     * @return array
     */
    public function updateStatusSeleksi($pendaftar_id, $status_baru, $deskripsi = null)
    {
        try {
            // Validasi status
            $allowedStatus = ['Diterima', 'Ditolak'];
            if (!in_array($status_baru, $allowedStatus)) {
                return [
                    'success' => false,
                    'message' => 'Status tidak valid. Hanya "Diterima" atau "Ditolak"'
                ];
            }

            if ($status_baru === 'Diterima') {
                // Call sp_terima_anggota - otomatis update status & insert ke mst_mahasiswa
                $query = "CALL sp_terima_anggota(:pendaftar_id)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':pendaftar_id', $pendaftar_id, PDO::PARAM_INT);
            } else {
                // Call sp_update_status_seleksi dengan deskripsi
                $query = "CALL sp_update_status_seleksi(:pendaftar_id, :status, :deskripsi)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':pendaftar_id', $pendaftar_id, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status_baru);
                $stmt->bindParam(':deskripsi', $deskripsi);
            }

            $stmt->execute();

            return [
                'success' => true,
                'message' => "Status berhasil diubah menjadi {$status_baru}"
            ];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            // Handle specific errors dari SP
            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Data pendaftar tidak ditemukan'
                ];
            }

            if (strpos($errorMessage, 'sudah menjadi anggota') !== false) {
                return [
                    'success' => false,
                    'message' => 'Mahasiswa dengan NIM ini sudah menjadi anggota lab'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal update status: ' . $errorMessage
            ];
        }
    }

    /**
     * Delete pendaftar by ID
     *
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            // Get data pendaftar dulu untuk ambil file_cv dan file_khs
            $pendaftar = $this->getById($id);

            if (!$pendaftar['success']) {
                return [
                    'success' => false,
                    'message' => 'Data pendaftar tidak ditemukan'
                ];
            }

            // Delete dari database
            $query = "DELETE FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Delete files (CV dan KHS)
            $data = $pendaftar['data'];
            if (!empty($data['file_cv'])) {
                FileUploadHelper::delete($data['file_cv'], 'cv');
            }
            if (!empty($data['file_khs'])) {
                FileUploadHelper::delete($data['file_khs'], 'khs');
            }

            return [
                'success' => true,
                'message' => 'Data pendaftar berhasil dihapus'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }
}
