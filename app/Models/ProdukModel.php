<?php

/**
 * ============================================================================
 * PRODUK MODEL 
 * ============================================================================
 * 
 * File: Models/ProdukModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data produk
 * 
 * Tabel yang digunakan:
 * - mst_produk: Data utama produk
 * - mst_dosen: Data dosen (untuk join author)
 * - map_produk_dosen: Mapping many-to-many produk-dosen
 * 
 * Fungsi utama:
 * - insert(): Insert data produk baru (via SP) dengan multiple dosen
 * - getAllProduk(): Ambil semua data produk
 * - getAllProdukPaginated(): Ambil data dengan pagination
 * - getProdukById(): Ambil detail produk berdasarkan ID (dengan join dosen)
 * - updateProduk(): Update data produk (via SP) dengan multiple dosen
 * - deleteProduk(): Hapus produk (via SP)
 * - getAllDosen(): Ambil semua dosen untuk dropdown
 */

class ProdukModel extends BaseModel
{
    protected $table_name = 'mst_produk';
    protected $view_name = 'vw_show_produk'; // ✅ Tambahkan view

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * GET ALL PRODUK WITH SEARCH AND FILTER
     * 
     * Fungsi: Ambil semua produk dengan search dan pagination
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

            // Search by nama produk OR author (dosen_names OR tim_mahasiswa)
            if (!empty($search)) {
                $whereConditions[] = "(
                LOWER(nama_produk) LIKE :search 
                OR LOWER(dosen_names) LIKE :search 
                OR LOWER(tim_mahasiswa) LIKE :search
            )";
                $bindParams[':search'] = '%' . strtolower($search) . '%';
            }

            // Combine WHERE conditions
            $whereClause = '';
            if (!empty($whereConditions)) {
                $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            }

            // Count total records dari VIEW
            $countQuery = "SELECT COUNT(*) as total FROM {$this->view_name} $whereClause";
            $countStmt = $this->db->prepare($countQuery);
            foreach ($bindParams as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Get data with pagination dari VIEW
            $query = "
            SELECT * 
            FROM {$this->view_name}
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
            error_log("ProdukModel getAllWithSearchAndFilter error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil data produk: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ];
        }
    }

    /**
     * ✅ GET ALL PRODUK - Gunakan VIEW
     */
    public function getAllProduk()
    {
        try {
            // ✅ Query dari VIEW (lebih simple & konsisten)
            $query = "SELECT * FROM {$this->view_name} ORDER BY created_at DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data produk',
                'data' => $result
            ];
        } catch (PDOException $e) {
            error_log("ProdukModel getAllProduk error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data produk',
                'data' => []
            ];
        }
    }

    /**
     * ✅ GET ALL PRODUK (PAGINATION) - Gunakan VIEW
     */
    public function getAllProdukPaginated($limit = 10, $offset = 0, $countOnly = false)
    {
        try {
            // 1. Hitung total records dari VIEW
            $countQuery = "SELECT COUNT(*) as total FROM {$this->view_name}";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            if ($countOnly) {
                return [
                    'success' => true,
                    'data' => [],
                    'total' => (int)$totalRecords
                ];
            }

            // 2. Ambil data dengan pagination dari VIEW
            $query = "SELECT * FROM {$this->view_name}
                      ORDER BY created_at DESC
                      LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data produk',
                'data' => $result,
                'total' => (int)$totalRecords
            ];
        } catch (PDOException $e) {
            error_log("ProdukModel getAllProdukPaginated error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data produk',
                'data' => [],
                'total' => 0
            ];
        }
    }

    /**
     * ✅ GET PRODUK BY ID - Ambil dari mst_produk + manual join
     */
    public function getProdukById($id)
    {
        try {
            // Ambil data produk
            $query = "SELECT * FROM {$this->view_name} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $produk = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$produk) {
                return [
                    'success' => false,
                    'message' => 'Data produk tidak ditemukan',
                    'data' => null
                ];
            }

            // Ambil dosen yang terkait dengan produk ini
            $dosenQuery = "SELECT d.id, d.full_name
                          FROM map_produk_dosen mpd
                          JOIN mst_dosen d ON mpd.dosen_id = d.id
                          WHERE mpd.produk_id = :produk_id
                          ORDER BY d.full_name";

            $dosenStmt = $this->db->prepare($dosenQuery);
            $dosenStmt->bindParam(':produk_id', $id, PDO::PARAM_INT);
            $dosenStmt->execute();

            $dosenList = $dosenStmt->fetchAll(PDO::FETCH_ASSOC);

            // Tambahkan dosen list ke produk data
            $produk['dosen_list'] = $dosenList;
            $produk['dosen_ids'] = array_column($dosenList, 'id');
            $produk['dosen_names'] = implode(', ', array_column($dosenList, 'full_name'));

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan detail produk',
                'data' => $produk
            ];
        } catch (PDOException $e) {
            error_log("ProdukModel getProdukById error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan detail produk',
                'data' => null
            ];
        }
    }

    /**
     * INSERT PRODUK (Implement dari BaseModel)
     * 
     * Fungsi: Insert data produk baru menggunakan stored procedure dengan multiple dosen
     * @param array $data - Data produk ['nama_produk', 'deskripsi', 'foto_produk', 'link_produk', 'dosen_ids' => array, 'tim_mahasiswa']
     * @return array - ['success' => bool, 'message' => string, 'data' => ['id' => int]]
     */
    public function insert($data)
    {
        try {
            // Convert dosen_ids array ke PostgreSQL array format
            $dosenIdsArray = null;
            if (!empty($data['dosen_ids']) && is_array($data['dosen_ids'])) {
                $dosenIdsArray = '{' . implode(',', $data['dosen_ids']) . '}';
            }

            // Gunakan CALL untuk stored procedure
            $query = "CALL sp_insert_produk(
                :nama_produk,
                :deskripsi,
                :foto_produk,
                :link_produk,
                :tim_mahasiswa,
                :dosen_ids
            )";

            $stmt = $this->db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':nama_produk', $data['nama_produk'], PDO::PARAM_STR);
            $stmt->bindParam(':deskripsi', $data['deskripsi'], PDO::PARAM_STR);
            $stmt->bindParam(':foto_produk', $data['foto_produk'], PDO::PARAM_STR);

            // Handle NULL values
            if (empty($data['link_produk'])) {
                $stmt->bindValue(':link_produk', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':link_produk', $data['link_produk'], PDO::PARAM_STR);
            }

            if (empty($data['tim_mahasiswa'])) {
                $stmt->bindValue(':tim_mahasiswa', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':tim_mahasiswa', $data['tim_mahasiswa'], PDO::PARAM_STR);
            }

            // Bind dosen_ids array
            if ($dosenIdsArray === null) {
                $stmt->bindValue(':dosen_ids', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':dosen_ids', $dosenIdsArray, PDO::PARAM_STR);
            }

            // Execute procedure
            $stmt->execute();

            // Ambil ID baru yang baru saja diinsert
            $lastIdQuery = "SELECT CURRVAL(pg_get_serial_sequence('mst_produk', 'id')) as last_id";
            $lastIdStmt = $this->db->prepare($lastIdQuery);
            $lastIdStmt->execute();
            $result = $lastIdStmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => [
                    'id' => $result['last_id'] ?? null
                ]
            ];
        } catch (PDOException $e) {
            error_log("ProdukModel insert error: " . $e->getMessage());

            // Handle error dari stored procedure
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'Nama produk sudah terdaftar') !== false) {
                return [
                    'success' => false,
                    'message' => 'Nama produk sudah terdaftar dalam sistem'
                ];
            }

            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Salah satu dosen yang dipilih tidak ditemukan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menambahkan data produk: ' . $errorMessage
            ];
        }
    }

    /**
     * UPDATE PRODUK
     * 
     * Fungsi: Update data produk menggunakan stored procedure dengan multiple dosen
     * @param int $id - ID produk yang akan diupdate
     * @param array $data - Data yang akan diupdate
     * @return array - ['success' => bool, 'message' => string]
     */
    public function update($id, $data)
    {
        try {
            // Convert dosen_ids array ke PostgreSQL array format
            $dosenIdsArray = null;
            if (!empty($data['dosen_ids']) && is_array($data['dosen_ids'])) {
                $dosenIdsArray = '{' . implode(',', $data['dosen_ids']) . '}';
            }

            // Gunakan CALL
            $query = "CALL sp_update_produk(
                :id,
                :nama_produk,
                :deskripsi,
                :foto_produk,
                :link_produk,
                :tim_mahasiswa,
                :dosen_ids
            )";

            $stmt = $this->db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nama_produk', $data['nama_produk'], PDO::PARAM_STR);
            $stmt->bindParam(':deskripsi', $data['deskripsi'], PDO::PARAM_STR);
            $stmt->bindParam(':foto_produk', $data['foto_produk'], PDO::PARAM_STR);

            // Handle NULL values
            if (empty($data['link_produk'])) {
                $stmt->bindValue(':link_produk', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':link_produk', $data['link_produk'], PDO::PARAM_STR);
            }

            if (empty($data['tim_mahasiswa'])) {
                $stmt->bindValue(':tim_mahasiswa', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':tim_mahasiswa', $data['tim_mahasiswa'], PDO::PARAM_STR);
            }

            // Bind dosen_ids array
            if ($dosenIdsArray === null) {
                $stmt->bindValue(':dosen_ids', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':dosen_ids', $dosenIdsArray, PDO::PARAM_STR);
            }

            // Execute procedure
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data produk berhasil diupdate'
            ];
        } catch (PDOException $e) {
            error_log("ProdukModel updateProduk error: " . $e->getMessage());

            // Handle error dari stored procedure
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'Nama produk sudah terdaftar') !== false) {
                return [
                    'success' => false,
                    'message' => 'Nama produk sudah terdaftar dalam sistem'
                ];
            }

            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Data produk atau dosen tidak ditemukan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate data produk'
            ];
        }
    }

    /**
     * DELETE PRODUK
     *
     * Fungsi: Hapus produk dari database menggunakan stored procedure
     * @param int $id - ID produk yang akan dihapus
     * @return array - ['success' => bool, 'message' => string, 'data' => ['foto_produk' => string]]
     */
    public function delete($id)
    {
        try {
            // Ambil data foto SEBELUM delete
            $checkQuery = "SELECT foto_produk FROM {$this->table_name} WHERE id = :id LIMIT 1";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            $produk = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$produk) {
                return [
                    'success' => false,
                    'message' => 'Data produk tidak ditemukan'
                ];
            }

            // Panggil SP untuk menghapus data
            $deleteQuery = "CALL sp_delete_produk(:id)";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();

            return [
                'success' => true,
                'message' => 'Data produk berhasil dihapus',
                'data' => [
                    'foto_produk' => $produk['foto_produk']
                ]
            ];
        } catch (PDOException $e) {
            error_log("ProdukModel deleteProduk error: " . $e->getMessage());

            // Handle error dari stored procedure
            if (strpos($e->getMessage(), 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Data produk tidak ditemukan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghapus data produk'
            ];
        }
    }

    /**
     * GET ALL DOSEN (untuk dropdown)
     * 
     * Fungsi: Mengambil semua data dosen untuk digunakan di dropdown
     * @return array - ['success' => bool, 'data' => array]
     */
    public function getAllDosen()
    {
        try {
            $query = "SELECT id, full_name FROM mst_dosen ORDER BY full_name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data dosen',
                'data' => $result
            ];
        } catch (PDOException $e) {
            error_log("ProdukModel getAllDosen error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data dosen',
                'data' => []
            ];
        }
    }
}
