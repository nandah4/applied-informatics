<?php

/**
 * File: Models/RecruitmentModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data recruitment
 *
 * PERUBAHAN UTAMA:
 * - Tidak lagi menggunakan view (vw_show_recruitment)
 * - Query langsung ke tabel trx_rekrutmen
 * - Auto-close expired recruitment dipanggil sebelum setiap operasi read
 *
 * Tabel yang digunakan:
 * - trx_rekrutmen: Data recruitment
 *
 * Fungsi utama:
 * - insert(): Insert data recruitment baru (status auto-determined)
 * - getAll(): Ambil semua data recruitment
 * - getById(): Ambil detail recruitment berdasarkan ID
 * - update(): Update data recruitment (status auto-determined)
 * - delete(): Hapus recruitment
 */

class RecruitmentModel extends BaseModel
{
    protected $table_name = "trx_rekrutmen";

    /**
     * Ambil semua data recruitment dengan pagination
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllRecruitmentWithPagination($limit = 10, $offset = 0)
    {
        try {
            // Auto-close expired recruitments sebelum fetch data
            $this->autoCloseExpiredRecruitments();

            // Count total records
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name}";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Get data dengan pagination
            $query = "
                SELECT 
                    id,
                    judul,
                    deskripsi,
                    status,
                    tanggal_buka,
                    tanggal_tutup,
                    lokasi,
                    created_at,
                    updated_at
                FROM {$this->table_name} 
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
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
     * GET ALL RECRUITMENT WITH SEARCH AND FILTER
     * Ambil semua data recruitment dengan search dan pagination
     * Mencari berdasarkan judul, status, dan lokasi.
     * @param array $params - ['search' => string, 'limit' => int, 'offset' => int]
     * @return array - ['success' => bool, 'data' => array, 'total' => int]
     */
    public function getAllWithSearchAndFilter($params = [])
    {
        try {
            // Auto-close expired recruitments sebelum fetch data
            $this->autoCloseExpiredRecruitments();

            // Extract parameters
            $search = $params['search'] ?? '';
            $limit = $params['limit'] ?? 10;
            $offset = $params['offset'] ?? 0;

            // Build WHERE clause
            $whereConditions = [];
            $bindParams = [];

            // 1. Search: Judul ATAU Lokasi ATAU Status
            if (!empty($search)) {
                $searchLower = '%' . strtolower($search) . '%';

                $whereConditions[] = "(
                    LOWER(judul) LIKE :search 
                    OR LOWER(lokasi) LIKE :search
                    OR LOWER(status::text) LIKE :search
                )";
                $bindParams[':search'] = $searchLower;
            }

            // Combine WHERE conditions
            $whereClause = '';
            if (!empty($whereConditions)) {
                $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            }

            // Count total records
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name} $whereClause";
            $countStmt = $this->db->prepare($countQuery);

            // Bind search params for count
            foreach ($bindParams as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Get data dengan pagination
            $query = "
                SELECT 
                    id,
                    judul,
                    deskripsi,
                    status,
                    tanggal_buka,
                    tanggal_tutup,
                    lokasi,
                    created_at,
                    updated_at
                FROM {$this->table_name} 
                $whereClause
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset
            ";

            $stmt = $this->db->prepare($query);

            // Bind search params for data
            foreach ($bindParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            // Bind pagination params
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $result,
                'total' => (int)$totalRecords
            ];
        } catch (PDOException $e) {
            error_log("RecruitmentModel getAllWithSearchAndFilter error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ];
        }
    }

    /**
     * Get recruitment by ID
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        try {
            // Auto-close expired recruitments sebelum fetch data
            $this->autoCloseExpiredRecruitments();

            $query = "
                SELECT 
                    id,
                    judul,
                    deskripsi,
                    status,
                    tanggal_buka,
                    tanggal_tutup,
                    lokasi,
                    created_at,
                    updated_at
                FROM {$this->table_name} 
                WHERE id = :id
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Recruitment tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $result,
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Insert new recruitment
     * STATUS DITENTUKAN OTOMATIS oleh stored procedure berdasarkan tanggal
     * 
     * @param array $data
     * @return array
     */
    public function insert($data)
    {
        try {
            $query = "CALL sp_insert_recruitment (:judul, :deskripsi, :status, :tanggal_buka, :tanggal_tutup, :lokasi)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':judul', $data['judul']);
            $stmt->bindParam(':deskripsi', $data['deskripsi']);
            $stmt->bindParam(':status', $data['status']); // Will be overridden by SP
            $stmt->bindParam(':tanggal_buka', $data['tanggal_buka']);
            $stmt->bindParam(':tanggal_tutup', $data['tanggal_tutup']);
            $stmt->bindParam(':lokasi', $data['lokasi']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data recruitment berhasil ditambahkan'
            ];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            // Error dari procedure (RAISE EXCEPTION)
            if (strpos($errorMessage, 'Tanggal tutup tidak boleh lebih awal dari tanggal buka') !== false) {
                return [
                    'success' => false,
                    'message' => 'Tanggal tutup tidak boleh lebih awal dari tanggal buka'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menambahkan data recruitment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update recruitment
     * STATUS DITENTUKAN OTOMATIS oleh stored procedure berdasarkan tanggal
     * 
     * LOGIKA AUTO-STATUS:
     * - Jika tanggal_tutup < CURRENT_DATE -> status = 'tutup' (expired/diperpendek)
     * - Jika tanggal_tutup >= CURRENT_DATE -> status = 'buka' (aktif/diperpanjang)
     * 
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        try {
            $query = "CALL sp_update_recruitment (:id, :judul, :deskripsi, :status, :tanggal_buka, :tanggal_tutup, :lokasi)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':judul', $data['judul']);
            $stmt->bindParam(':deskripsi', $data['deskripsi']);
            $stmt->bindParam(':status', $data['status']); // Will be overridden by SP
            $stmt->bindParam(':tanggal_buka', $data['tanggal_buka']);
            $stmt->bindParam(':tanggal_tutup', $data['tanggal_tutup']);
            $stmt->bindParam(':lokasi', $data['lokasi']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data berhasil diupdate'
            ];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'Tanggal tutup tidak boleh lebih awal dari tanggal buka') !== false) {
                return [
                    'success' => false,
                    'message' => 'Tanggal tutup tidak boleh lebih awal dari tanggal buka'
                ];
            }

            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Data recruitment tidak ditemukan (ID mungkin salah).'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal update data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete recruitment
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            $query = "CALL sp_delete_recruitment(:id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Data recruitment tidak ditemukan (ID mungkin salah).'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Auto-close expired recruitments
     * Memanggil function untuk update status recruitment yang sudah melewati tanggal tutup
     * 
     * Function ini dipanggil sebelum setiap operasi READ untuk memastikan
     * status recruitment selalu up-to-date tanpa perlu cronjob
     */
    private function autoCloseExpiredRecruitments()
    {
        try {
            $query = "SELECT fn_auto_close_expired_recruitment()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            // Optional: ambil jumlah record yang diupdate untuk logging
            $result = $stmt->fetch(PDO::FETCH_NUM);
            $updatedCount = $result[0] ?? 0;

            // Uncomment untuk debugging
            // error_log("Auto-closed {$updatedCount} expired recruitment(s)");

        } catch (PDOException $e) {
            // Log error tapi jangan stop execution
            error_log('Auto-close expired recruitments failed: ' . $e->getMessage());
        }
    }

    /**
     * Manual trigger untuk auto-close (optional)
     * Bisa dipanggil dari cronjob atau admin panel
     *
     * @return array
     */
    public function manualAutoClose()
    {
        try {
            $query = "SELECT fn_auto_close_expired_recruitment()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_NUM);
            $updatedCount = $result[0] ?? 0;

            return [
                'success' => true,
                'message' => "Berhasil menutup {$updatedCount} recruitment yang expired",
                'count' => $updatedCount
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal auto-close: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }

    /**
     * Insert pendaftar baru menggunakan stored procedure
     * Dipanggil saat mahasiswa submit form pendaftaran
     *
     * @param array $data - Data pendaftar dari form
     * @return array
     */
    public function insertPendaftar($data)
    {
        try {
            $query = "CALL sp_daftar_rekrutmen(
                :rekrutmen_id,
                :nim,
                :nama,
                :email,
                :no_hp,
                :semester,
                :ipk,
                :link_portfolio,
                :link_github,
                :file_cv,
                :file_khs
            )";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':rekrutmen_id', $data['rekrutmen_id'], PDO::PARAM_INT);
            $stmt->bindParam(':nim', $data['nim']);
            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':no_hp', $data['no_hp']);
            $stmt->bindParam(':semester', $data['semester'], PDO::PARAM_INT);
            $stmt->bindParam(':ipk', $data['ipk']);
            $stmt->bindParam(':link_portfolio', $data['link_portfolio']);
            $stmt->bindParam(':link_github', $data['link_github']);
            $stmt->bindParam(':file_cv', $data['file_cv']);
            $stmt->bindParam(':file_khs', $data['file_khs']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Pendaftaran berhasil! Silakan cek email atau WhatsApp untuk informasi selanjutnya.'
            ];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            // Handle specific error dari stored procedure
            if (strpos($errorMessage, 'tidak ditemukan atau sudah ditutup') !== false) {
                return [
                    'success' => false,
                    'message' => 'Lowongan rekrutmen tidak ditemukan atau sudah ditutup.'
                ];
            }

            if (strpos($errorMessage, 'sudah mendaftar') !== false) {
                return [
                    'success' => false,
                    'message' => 'Anda sudah mendaftar pada lowongan ini sebelumnya.'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mendaftar: ' . $errorMessage
            ];
        }
    }
}
