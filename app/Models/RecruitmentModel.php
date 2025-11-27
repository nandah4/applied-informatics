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
}