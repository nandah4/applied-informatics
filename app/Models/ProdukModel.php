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
 * - tbl_produk: Data utama produk
 * - tbl_dosen: Data dosen (untuk join author)
 * 
 * Fungsi utama:
 * - insert(): Insert data produk baru (via SP)
 * - getAllProduk(): Ambil semua data produk
 * - getAllProdukPaginated(): Ambil data dengan pagination
 * - getProdukById(): Ambil detail produk berdasarkan ID (dengan join dosen)
 * - updateProduk(): Update data produk (via SP)
 * - deleteProduk(): Hapus produk (via SP)
 * - getAllDosen(): Ambil semua dosen untuk dropdown
 */

class ProdukModel extends BaseModel
{
    protected $table_name = 'tbl_produk';

    /**
     * CONSTRUCTOR
     * Inisialisasi koneksi database via BaseModel
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * GET ALL PRODUK
     * Fungsi: Mengambil semua data produk dengan join ke tbl_dosen untuk nama author
     * @return array
     */
    public function getAllProduk()
    {
        try {
            $query = "SELECT 
                        p.id,
                        p.nama_produk,
                        p.deskripsi,
                        p.foto_produk,
                        p.link_produk,
                        p.author_dosen_id,
                        p.author_mahasiswa_nama,
                        p.created_at,
                        p.updated_at,
                        d.full_name as dosen_name
                      FROM {$this->table_name} p
                      LEFT JOIN tbl_dosen d ON p.author_dosen_id = d.id
                      ORDER BY p.id DESC";

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
     * GET ALL PRODUK (PAGINATION)
     *
     * Fungsi: Mengambil data dengan batasan per halaman + join dosen
     * @param int $limit - Jumlah data per halaman
     * @param int $offset - Mulai dari data ke berapa
     * @param bool $countOnly - Jika true, hanya kembalikan total
     * @return array - ['success' => bool, 'data' => array, 'total' => int]
     */
    public function getAllProdukPaginated($limit = 10, $offset = 0, $countOnly = false)
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

            // 2. Ambil data dengan pagination + join dosen
            $query = "SELECT 
                        p.id,
                        p.nama_produk,
                        p.deskripsi,
                        p.foto_produk,
                        p.link_produk,
                        p.author_dosen_id,
                        p.author_mahasiswa_nama,
                        p.created_at,
                        p.updated_at,
                        d.full_name as dosen_name
                      FROM {$this->table_name} p
                      LEFT JOIN tbl_dosen d ON p.author_dosen_id = d.id
                      ORDER BY p.id DESC
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
     * GET PRODUK BY ID
     * 
     * Fungsi: Mengambil detail 1 produk berdasarkan ID (dengan join dosen)
     * @param int $id - ID produk
     * @return array - ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function getProdukById($id)
    {
        try {
            $query = "SELECT 
                        p.id,
                        p.nama_produk,
                        p.deskripsi,
                        p.foto_produk,
                        p.link_produk,
                        p.author_dosen_id,
                        p.author_mahasiswa_nama,
                        p.created_at,
                        p.updated_at,
                        d.full_name as dosen_name
                      FROM {$this->table_name} p
                      LEFT JOIN tbl_dosen d ON p.author_dosen_id = d.id
                      WHERE p.id = :id 
                      LIMIT 1";

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
     * Fungsi: Insert data produk baru menggunakan stored procedure
     * @param array $data - Data produk ['nama_produk', 'deskripsi', 'foto_produk', 'link_produk', 'author_dosen_id', 'author_mahasiswa_nama']
     * @return array - ['success' => bool, 'message' => string, 'data' => ['id' => int]]
     */
    public function insert($data)
    {
        try {
            // Gunakan CALL untuk stored procedure
            $query = "CALL sp_insert_produk(
                :nama_produk,
                :deskripsi,
                :foto_produk,
                :link_produk,
                :author_dosen_id,
                :author_mahasiswa_nama
            )";

            $stmt = $this->db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':nama_produk', $data['nama_produk'], PDO::PARAM_STR);
            $stmt->bindParam(':deskripsi', $data['deskripsi'], PDO::PARAM_STR);
            $stmt->bindParam(':foto_produk', $data['foto_produk'], PDO::PARAM_STR);

            // Handle NULL values untuk link_produk
            if (empty($data['link_produk'])) {
                $stmt->bindValue(':link_produk', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':link_produk', $data['link_produk'], PDO::PARAM_STR);
            }

            // Handle NULL values untuk author_dosen_id
            if (empty($data['author_dosen_id'])) {
                $stmt->bindValue(':author_dosen_id', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':author_dosen_id', $data['author_dosen_id'], PDO::PARAM_INT);
            }

            // Handle NULL values untuk author_mahasiswa_nama
            if (empty($data['author_mahasiswa_nama'])) {
                $stmt->bindValue(':author_mahasiswa_nama', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':author_mahasiswa_nama', $data['author_mahasiswa_nama'], PDO::PARAM_STR);
            }

            // Execute procedure
            $stmt->execute();

            // Ambil ID baru yang baru saja diinsert
            $lastIdQuery = "SELECT CURRVAL(pg_get_serial_sequence('tbl_produk', 'id')) as last_id";
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

            if (strpos($errorMessage, 'Minimal salah satu author') !== false) {
                return [
                    'success' => false,
                    'message' => 'Minimal salah satu author (dosen atau mahasiswa) harus diisi'
                ];
            }

            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Dosen yang dipilih tidak ditemukan'
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
     * Fungsi: Update data produk menggunakan stored procedure
     * @param int $id - ID produk yang akan diupdate
     * @param array $data - Data yang akan diupdate
     * @return array - ['success' => bool, 'message' => string]
     */
    public function update($id, $data)
    {
        try {
            // Gunakan CALL
            $query = "CALL sp_update_produk(
                :id,
                :nama_produk,
                :deskripsi,
                :foto_produk,
                :link_produk,
                :author_dosen_id,
                :author_mahasiswa_nama
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

            if (empty($data['author_dosen_id'])) {
                $stmt->bindValue(':author_dosen_id', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':author_dosen_id', $data['author_dosen_id'], PDO::PARAM_INT);
            }

            if (empty($data['author_mahasiswa_nama'])) {
                $stmt->bindValue(':author_mahasiswa_nama', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':author_mahasiswa_nama', $data['author_mahasiswa_nama'], PDO::PARAM_STR);
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
                    'message' => 'Data produk tidak ditemukan'
                ];
            }

            if (strpos($errorMessage, 'Minimal salah satu author') !== false) {
                return [
                    'success' => false,
                    'message' => 'Minimal salah satu author (dosen atau mahasiswa) harus diisi'
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
            // Karena stored procedure tidak return nilai foto
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
            $query = "SELECT id, full_name FROM tbl_dosen ORDER BY full_name ASC";
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
