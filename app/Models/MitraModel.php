<?php

/**
 * File: Models/MitraModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data mitra
 *
 * Tabel yang digunakan:
 * - tbl_mitra: Data utama mitra kerjasama
 *
 * Fungsi utama:
 * - insert(): Insert data mitra baru
 * - getAll(): Ambil semua data mitra
 * - getById(): Ambil detail mitra berdasarkan ID
 * - update(): Update data mitra
 * - delete(): Hapus mitra
 */

class MitraModel extends BaseModel
{
    protected $table_name = "mst_mitra";

    public function getMitraByKategori($kategori = "industri")
    {
        try {
            $query = "SELECT id, nama, status, kategori, logo_mitra FROM {$this->table_name} WHERE kategori = :kategori AND status = 'aktif' ORDER BY tanggal_mulai";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":kategori", $kategori, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (PDOException $e) {
            error_log("MitraModel getMitraByKategori error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data mitra',
                'data' => []
            ];
        }
    }
    /**
     * GET ALL MITRA WITH SEARCH AND FILTER
     * 
     * @param array $params - ['search' => string, 'limit' => int, 'offset' => int]
     * @return array - ['success' => bool, 'data' => array, 'total' => int]
     */
    public function getAllWithSearchAndFilter($params = [])
    {
        try {
            // Extract parameters
            $search = $params['search'] ?? '';
            $limit = $params['limit'] ?? 10;
            $offset = $params['offset'] ?? 0;

            // Build WHERE clause
            $whereConditions = [];
            $bindParams = [];

            // Search by nama, kategori, OR status
            if (!empty($search)) {
                $whereConditions[] = "(
                LOWER(nama) LIKE :search 
                OR LOWER(CAST(kategori AS TEXT)) LIKE :search 
                OR LOWER(CAST(status AS TEXT)) LIKE :search
            )";
                $bindParams[':search'] = '%' . strtolower($search) . '%';
            }

            // Combine WHERE conditions
            $whereClause = '';
            if (!empty($whereConditions)) {
                $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            }

            // Count total records
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name} $whereClause";
            $countStmt = $this->db->prepare($countQuery);
            foreach ($bindParams as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Get data with pagination
            $query = "
            SELECT 
                id, nama, status, logo_mitra, kategori, 
                tanggal_mulai, tanggal_akhir, created_at, updated_at
            FROM {$this->table_name}
            $whereClause
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";

            $stmt = $this->db->prepare($query);

            // Bind search params
            foreach ($bindParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            // Bind pagination params
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $data,
                'total' => (int) $totalRecords
            ];
        } catch (PDOException $e) {
            error_log("MitraModel getAllWithSearchAndFilter error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil data mitra: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ];
        }
    }

    /**
     * Ambil semua data mitra
     * @return array
     */
    public function getAllMitraWithPagination($limit = 10, $offset = 0)
    {
        try {
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name}";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            $query = "SELECT
                        m.id,
                        m.nama,          
                        m.status,        
                        m.logo_mitra,
                        m.kategori,
                        m.tanggal_mulai,
                        m.tanggal_akhir,
                        m.created_at,
                        m.updated_at
                    FROM mst_mitra m ORDER BY created_at DESC
                    LIMIT :limit OFFSET :offset";

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
     * Get mitra by ID
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        try {
            $query = "SELECT
                        m.id,
                        m.nama,          
                        m.status,        
                        m.logo_mitra,
                        m.kategori,
                        m.tanggal_mulai,
                        m.tanggal_akhir,
                        m.created_at,
                        m.updated_at
                    FROM mst_mitra m WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Mitra tidak ditemukan'
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
     * Insert new mitra
     * @param array $data
     * @return array
     */
    public function insert($data)
    {
        try {
            $query = "CALL sp_insert_mitra (:nama, :status, :kategori_mitra, :logo_mitra, :tanggal_mulai, :tanggal_akhir)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':kategori_mitra', $data['kategori_mitra']);
            $stmt->bindParam(':logo_mitra', $data['logo_mitra']);
            $stmt->bindParam(':tanggal_mulai', $data['tanggal_mulai']);
            $stmt->bindParam(':tanggal_akhir', $data['tanggal_akhir']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data mitra berhasil ditambahkan'
            ];
        } catch (PDOException $e) {
            // Cek apakah error dari RAISE EXCEPTION di procedure
            $errorMessage = $e->getMessage();

            // Error dari procedure (RAISE EXCEPTION)
            if (strpos($errorMessage, 'Tanggal mulai tidak boleh lebih dari tanggal akhir') !== false) {
                return [
                    'success' => false,
                    'message' => 'Tanggal mulai tidak boleh lebih dari tanggal akhir'
                ];
            }
            return [
                'success' => false,
                'message' => 'Gagal menambahkan data mitra: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update mitra
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        try {
            $query = "CALL sp_update_mitra (:id, :nama, :status, :kategori_mitra, :logo_mitra, :tanggal_mulai, :tanggal_akhir)";
            $stmt = $this->db->prepare($query);


            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':kategori_mitra', $data['kategori_mitra']);
            $stmt->bindParam(':logo_mitra', $data['logo_mitra']);
            $stmt->bindParam(':tanggal_mulai', $data['tanggal_mulai']);
            $stmt->bindParam(':tanggal_akhir', $data['tanggal_akhir']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data berhasil diupdate'
            ];
        } catch (PDOException $e) {
            // Cek apakah error dari RAISE EXCEPTION di procedure
            $errorMessage = $e->getMessage();

            // Error dari procedure (RAISE EXCEPTION)
            if (strpos($errorMessage, 'Tanggal mulai tidak boleh lebih dari tanggal akhir') !== false) {
                return [
                    'success' => false,
                    'message' => 'Tanggal mulai tidak boleh lebih dari tanggal akhir'
                ];
            }

            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Data mitra tidak ditemukan (ID mungkin salah).'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal update data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete mitra
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {

            $mitra = $this->getById($id);

            if (!$mitra || !$mitra['success']) {
                return [
                    'success' => false,
                    'message' => 'Mitra tidak ditemukan'
                ];
            }

            $query = "DELETE FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'success' => true,
                'data' => ['logo_mitra' => $mitra['data']['logo_mitra'] ?? null],
                'message' => 'Data berhasil dihapus'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }
}
