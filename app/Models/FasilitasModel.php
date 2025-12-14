<?php

/**
 * ============================================================================
 * FASILITAS MODEL 
 * ============================================================================
 * 
 * File: Models/FasilitasModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data fasilitas
 * 
 * Tabel yang digunakan:
 * - mst_fasilitas: Data fasilitas laboratorium
 * 
 * Fungsi utama:
 * - insertFasilitas(): Insert data fasilitas baru (via SP)
 * - getAllFasilitas(): Ambil semua data fasilitas
 * - getAllFasilitasPaginated(): Ambil data dengan pagination
 * - getFasilitasById(): Ambil detail fasilitas berdasarkan ID
 * - updateFasilitas(): Update data fasilitas (via SP)
 * - deleteFasilitas(): Hapus fasilitas (via SP)
 * 
 * CATATAN:
 * - Tidak menggunakan view karena tidak ada join di schema
 * - Query langsung ke tabel mst_fasilitas
 * - Validasi dilakukan di stored procedure
 */

class FasilitasModel
{
    private $db;
    private $table_name = 'mst_fasilitas';

    /**
     * CONSTRUCTOR
     * Inisialisasi koneksi database
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * GET ALL FASILITAS
     * Fungsi: Mengambil semua data fasilitas (untuk keperluan non-pagination)
     * 
     * @return array - ['success' => bool, 'message' => string, 'data' => array]
     */
    public function getAllFasilitas()
    {
        try {
            // Query langsung ke tabel
            $query = "
                SELECT 
                    id,
                    nama,
                    deskripsi,
                    foto,
                    created_at,
                    updated_at
                FROM {$this->table_name}
                ORDER BY created_at DESC
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data fasilitas',
                'data' => $result
            ];
        } catch (PDOException $e) {
            error_log("FasilitasModel getAllFasilitas error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data fasilitas',
                'data' => []
            ];
        }
    }

    /**
     * GET ALL FASILITAS WITH SEARCH AND FILTER
     * 
     * Fungsi: Ambil semua fasilitas dengan search dan pagination
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

            // Search by nama fasilitas
            if (!empty($search)) {
                $whereConditions[] = "LOWER(nama) LIKE :search";
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
                id,
                nama,
                deskripsi,
                foto,
                created_at,
                updated_at
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
            error_log("FasilitasModel getAllWithSearchAndFilter error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil data fasilitas: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ];
        }
    }

    /**
     * GET ALL FASILITAS (PAGINATION)
     *
     * Fungsi: Mengambil data dengan batasan per halaman
     * 
     * @param int $limit - Jumlah data per halaman
     * @param int $offset - Mulai dari data ke berapa
     * @param bool $countOnly - Jika true, hanya kembalikan total
     * @return array - ['success' => bool, 'data' => array, 'total' => int]
     */
    public function getAllFasilitasPaginated($limit = 10, $offset = 0, $countOnly = false)
    {
        try {
            // 1. Hitung total records
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name}";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Jika $countOnly true, kembalikan total saja
            if ($countOnly) {
                return [
                    'success' => true,
                    'data' => [],
                    'total' => (int)$totalRecords
                ];
            }

            // 2. Ambil data dengan pagination
            $query = "
                SELECT 
                    id,
                    nama,
                    deskripsi,
                    foto,
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
                'message' => 'Berhasil mendapatkan data fasilitas',
                'data' => $result,
                'total' => (int)$totalRecords
            ];
        } catch (PDOException $e) {
            error_log("FasilitasModel getAllFasilitasPaginated error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data fasilitas',
                'data' => [],
                'total' => 0
            ];
        }
    }

    /**
     * GET FASILITAS BY ID
     * 
     * Fungsi: Mengambil detail 1 fasilitas berdasarkan ID
     * 
     * @param int $id - ID fasilitas
     * @return array - ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function getFasilitasById($id)
    {
        try {
            $query = "
                SELECT 
                    id,
                    nama,
                    deskripsi,
                    foto,
                    created_at,
                    updated_at
                FROM {$this->table_name} 
                WHERE id = :id 
                LIMIT 1
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $fasilitas = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$fasilitas) {
                return [
                    'success' => false,
                    'message' => 'Data fasilitas tidak ditemukan',
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan detail fasilitas',
                'data' => $fasilitas
            ];
        } catch (PDOException $e) {
            error_log("FasilitasModel getFasilitasById error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan detail fasilitas',
                'data' => null
            ];
        }
    }

    /**
     * INSERT FASILITAS
     * 
     * Fungsi: Insert data fasilitas baru menggunakan stored procedure
     * Validasi nama duplicate dilakukan di stored procedure (case-insensitive)
     * 
     * @param array $data - Data fasilitas ['nama', 'deskripsi', 'foto']
     * @return array - ['success' => bool, 'message' => string, 'data' => ['id' => int]]
     */
    public function insertFasilitas($data)
    {
        try {
            $query = "CALL sp_insert_fasilitas(
                :nama,
                :deskripsi,
                :foto
            )";

            $stmt = $this->db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':nama', $data['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':deskripsi', $data['deskripsi'], PDO::PARAM_STR);
            $stmt->bindParam(':foto', $data['foto'], PDO::PARAM_STR);

            // Execute procedure
            $stmt->execute();

            // Ambil ID terakhir yang baru saja diinsert
            // Menggunakan CURRVAL untuk mendapatkan last ID dari sequence
            $lastIdQuery = "SELECT CURRVAL(pg_get_serial_sequence('mst_fasilitas', 'id')) as last_id";
            $lastIdStmt = $this->db->prepare($lastIdQuery);
            $lastIdStmt->execute();
            $result = $lastIdStmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Fasilitas berhasil ditambahkan',
                'data' => [
                    'id' => $result['last_id'] ?? null
                ]
            ];
        } catch (PDOException $e) {
            error_log("FasilitasModel insertFasilitas error: " . $e->getMessage());

            // Handle error dari stored procedure
            if (strpos($e->getMessage(), 'Nama fasilitas sudah terdaftar') !== false) {
                return [
                    'success' => false,
                    'message' => 'Nama fasilitas sudah terdaftar dalam sistem'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menambahkan data fasilitas: ' . $e->getMessage()
            ];
        }
    }

    /**
     * UPDATE FASILITAS
     * 
     * Fungsi: Update data fasilitas menggunakan stored procedure
     * Validasi nama duplicate dan ID existence dilakukan di stored procedure
     * 
     * @param int $id - ID fasilitas yang akan diupdate
     * @param array $data - Data yang akan diupdate ['nama', 'deskripsi', 'foto']
     * @return array - ['success' => bool, 'message' => string]
     */
    public function updateFasilitas($id, $data)
    {
        try {
            $query = "CALL sp_update_fasilitas(
                :id,
                :nama,
                :deskripsi,
                :foto
            )";

            $stmt = $this->db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nama', $data['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':deskripsi', $data['deskripsi'], PDO::PARAM_STR);
            $stmt->bindParam(':foto', $data['foto'], PDO::PARAM_STR);

            // Execute procedure
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data fasilitas berhasil diupdate'
            ];
        } catch (PDOException $e) {
            error_log("FasilitasModel updateFasilitas error: " . $e->getMessage());

            // Handle error dari stored procedure
            if (strpos($e->getMessage(), 'Nama fasilitas sudah terdaftar') !== false) {
                return [
                    'success' => false,
                    'message' => 'Nama fasilitas sudah terdaftar dalam sistem'
                ];
            }

            if (strpos($e->getMessage(), 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Data fasilitas tidak ditemukan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate data fasilitas'
            ];
        }
    }

    /**
     * DELETE FASILITAS
     *
     * Fungsi: Hapus fasilitas dari database menggunakan stored procedure
     * 
     * CATATAN PENTING:
     * - Stored procedure TIDAK mengembalikan nama file foto
     * - Model harus SELECT foto terlebih dahulu sebelum memanggil SP
     * - Ini untuk keperluan delete file foto dari server
     * 
     * @param int $id - ID fasilitas yang akan dihapus
     * @return array - ['success' => bool, 'message' => string, 'data' => ['foto' => string]]
     */
    public function deleteFasilitas($id)
    {
        try {
            // 1. Ambil data foto SEBELUM delete
            // Karena stored procedure tidak return nilai foto
            $checkQuery = "SELECT foto FROM {$this->table_name} WHERE id = :id LIMIT 1";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            $fasilitas = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$fasilitas) {
                return [
                    'success' => false,
                    'message' => 'Data fasilitas tidak ditemukan'
                ];
            }

            // 2. Panggil stored procedure untuk delete data
            $deleteQuery = "CALL sp_delete_fasilitas(:id)";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // 3. Return success dengan info foto untuk dihapus dari server
            return [
                'success' => true,
                'message' => 'Data fasilitas berhasil dihapus',
                'data' => [
                    'foto' => $fasilitas['foto']
                ]
            ];
        } catch (PDOException $e) {
            error_log("FasilitasModel deleteFasilitas error: " . $e->getMessage());

            // Handle error dari stored procedure
            if (strpos($e->getMessage(), 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Data fasilitas tidak ditemukan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghapus data fasilitas'
            ];
        }
    }

    /**
     * OPTIONAL: GET FASILITAS COUNT
     * 
     * Fungsi: Mendapatkan jumlah total fasilitas
     * Berguna untuk dashboard/statistik
     * 
     * @return int
     */
    public function getTotalFasilitas()
    {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->table_name}";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("FasilitasModel getTotalFasilitas error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * OPTIONAL: SEARCH FASILITAS
     * 
     * Fungsi: Search fasilitas berdasarkan nama atau deskripsi
     * 
     * @param string $keyword - Kata kunci pencarian
     * @param int $limit - Limit hasil
     * @param int $offset - Offset untuk pagination
     * @return array
     */
    public function searchFasilitas($keyword, $limit = 10, $offset = 0)
    {
        try {
            $searchTerm = '%' . $keyword . '%';

            // Count total hasil search
            $countQuery = "
                SELECT COUNT(*) as total 
                FROM {$this->table_name}
                WHERE LOWER(nama) LIKE LOWER(:keyword1)
                   OR LOWER(deskripsi) LIKE LOWER(:keyword2)
            ";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->bindParam(':keyword1', $searchTerm, PDO::PARAM_STR);
            $countStmt->bindParam(':keyword2', $searchTerm, PDO::PARAM_STR);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Search dengan pagination
            $query = "
                SELECT 
                    id,
                    nama,
                    deskripsi,
                    foto,
                    created_at,
                    updated_at
                FROM {$this->table_name}
                WHERE LOWER(nama) LIKE LOWER(:keyword1)
                   OR LOWER(deskripsi) LIKE LOWER(:keyword2)
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':keyword1', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':keyword2', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Berhasil melakukan pencarian',
                'data' => $result,
                'total' => (int)$totalRecords
            ];
        } catch (PDOException $e) {
            error_log("FasilitasModel searchFasilitas error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal melakukan pencarian',
                'data' => [],
                'total' => 0
            ];
        }
    }
}
