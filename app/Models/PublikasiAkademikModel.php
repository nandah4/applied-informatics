<?php

/**
 * File: Models/PublikasiAkademikModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data publikasi akademik dosen
 *
 * Tabel yang digunakan:
 * - trx_publikasi: Data utama publikasi akademik
 * - mst_dosen: Data referensi untuk author
 * - vw_show_publikasi: View untuk menampilkan data publikasi dengan join dosen
 *
 * Stored Procedures yang digunakan:
 * - sp_insert_publikasi_akademik: Insert data publikasi baru
 * - sp_update_publikasi_akademik: Update data publikasi
 *
 * Fungsi utama:
 * - getAllWithPagination(): Ambil semua publikasi dengan pagination
 * - getById(): Ambil detail publikasi by ID
 * - insert(): Insert publikasi baru via stored procedure
 * - update(): Update publikasi via stored procedure
 * - delete(): Hapus publikasi dari database
 */

class PublikasiAkademikModel extends BaseModel
{
    protected $table_name = 'trx_publikasi';

    /**
     * Ambil semua data publikasi dengan pagination
     * Menggunakan view vw_show_publikasi
     *
     * @param int $limit - Jumlah data per halaman
     * @param int $offset - Data dimulai dari baris ke berapa
     * @return array
     */
    public function getAllWithPagination($limit = 10, $offset = 0)
    {
        try {
            // Hitung total records
            $countQuery = "SELECT COUNT(*) as total FROM vw_show_publikasi";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Ambil data dengan pagination
            $query = "SELECT * FROM vw_show_publikasi ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
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
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ];
        }
    }

    /**
     * Get publikasi by ID
     * Menggunakan view vw_show_publikasi
     *
     * @param int $id - ID publikasi
     * @return array - Format: ['success' => bool, 'data' => array|null, 'message' => string]
     */
    public function getById($id)
    {
        try {
            $query = "SELECT * FROM vw_show_publikasi WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return [
                    'success' => false,
                    'message' => 'Publikasi tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Insert publikasi akademik baru
     * Menggunakan stored procedure sp_insert_publikasi_akademik
     *
     * @param array $data - Format: [
     *                          'dosen_id' => int,
     *                          'judul' => string,
     *                          'url_publikasi' => string|null,
     *                          'tahun_publikasi' => int,
     *                          'tipe_publikasi' => string (Riset|Kekayaan Intelektual|PPM)
     *                      ]
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function insert($data)
    {
        try {
            $query = "CALL sp_insert_publikasi_akademik(
            :dosen_id, :judul, :url_publikasi, :tahun_publikasi, :tipe_publikasi
            )";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":dosen_id", $data["dosen_id"], PDO::PARAM_INT);
            $stmt->bindParam(":judul", $data["judul"], PDO::PARAM_STR);
            $stmt->bindParam(":url_publikasi", $data["url_publikasi"], PDO::PARAM_STR);
            $stmt->bindParam(":tahun_publikasi", $data["tahun_publikasi"], PDO::PARAM_INT);
            $stmt->bindParam(":tipe_publikasi", $data["tipe_publikasi"], PDO::PARAM_STR);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Publikasi akademik berhasil ditambahkan!'
            ];
        } catch (PDOException $e) {
            // Handle RAISE EXCEPTION dari stored procedure
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'Dosen dengan ID') !== false) {
                return ['success' => false, 'message' => 'Dosen tidak ditemukan'];
            }

            if (strpos($errorMessage, 'Judul publikasi tidak boleh kosong.') !== false) {
                return ['success' => false, 'message' => 'Judul publikasi tidak boleh kosong.'];
            }

            if (strpos($errorMessage, 'Tahun publikasi tidak valid.') !== false) {
                return ['success' => false, 'message' => 'Tahun publikasi tidak valid.'];
            }

            return [
                'success' => false,
                'message' => 'Gagal menambahkan publikasi akademik: ' . $errorMessage
            ];
        }
    }

    /**
     * Update publikasi akademik
     * Menggunakan stored procedure sp_update_publikasi_akademik
     *
     * @param int $id - ID publikasi yang akan diupdate
     * @param array $data - Format: [
     *                          'dosen_id' => int,
     *                          'judul' => string,
     *                          'url_publikasi' => string|null,
     *                          'tahun_publikasi' => int,
     *                          'tipe_publikasi' => string (Riset|Kekayaan Intelektual|PPM)
     *                      ]
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function update($id, $data)
    {
        try {
            $query = "CALL sp_update_publikasi_akademik(
            :id, :dosen_id, :judul, :url_publikasi, :tahun_publikasi, :tipe_publikasi
            )";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":dosen_id", $data["dosen_id"], PDO::PARAM_INT);
            $stmt->bindParam(":judul", $data["judul"], PDO::PARAM_STR);
            $stmt->bindParam(":url_publikasi", $data["url_publikasi"], PDO::PARAM_STR);
            $stmt->bindParam(":tahun_publikasi", $data["tahun_publikasi"], PDO::PARAM_INT);
            $stmt->bindParam(":tipe_publikasi", $data["tipe_publikasi"], PDO::PARAM_STR);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Publikasi akademik berhasil diupdate!'
            ];
        } catch (PDOException $e) {
            // Handle RAISE EXCEPTION dari stored procedure
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'Publikasi dengan ID') !== false) {
                return ['success' => false, 'message' => 'Publikasi tidak ditemukan'];
            }

            if (strpos($errorMessage, 'Dosen dengan ID') !== false) {
                return ['success' => false, 'message' => 'Dosen tidak ditemukan'];
            }

            if (strpos($errorMessage, 'Judul publikasi tidak boleh kosong.') !== false) {
                return ['success' => false, 'message' => 'Judul publikasi tidak boleh kosong.'];
            }

            if (strpos($errorMessage, 'Tahun publikasi tidak valid.') !== false) {
                return ['success' => false, 'message' => 'Tahun publikasi tidak valid.'];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate publikasi akademik: ' . $errorMessage
            ];
        }
    }

    /**
     * Hapus publikasi akademik
     * Melakukan pengecekan keberadaan data sebelum delete
     *
     * @param int $id - ID publikasi yang akan dihapus
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function delete($id)
    {
        try {
            // Cek apakah publikasi ada
            $checkQuery = $this->getById($id);

            if (!$checkQuery['success']) {
                return [
                    'success' => false,
                    'message' => 'Publikasi tidak ditemukan'
                ];
            }

            // Delete dari database
            $query = "DELETE FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Publikasi berhasil dihapus'
            ];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            return [
                'success' => false,
                'message' => 'Gagal menghapus publikasi: ' . $errorMessage
            ];
        }
    }

    /**
     * Ambil publikasi berdasarkan tahun
     * Menggunakan view vw_show_publikasi
     *
     * @param int $year - Tahun publikasi
     * @param int $limit - Batasan jumlah data (optional, default null = semua data)
     * @return array - Format: ['success' => bool, 'data' => array, 'message' => string]
     */
    public function getByYear($year, $limit = null)
    {
        try {
            $query = "SELECT * FROM vw_show_publikasi
                      WHERE tahun_publikasi = :year
                      ORDER BY created_at DESC";

            if ($limit !== null) {
                $query .= " LIMIT :limit";
            }

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);

            if ($limit !== null) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            }

            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil publikasi: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Ambil publikasi berdasarkan dosen ID
     * Menggunakan view vw_show_publikasi
     *
     * @param int $dosenId - ID dosen
     * @return array - Format: ['success' => bool, 'data' => array grouped by tipe_publikasi]
     */
    public function getByDosenId($dosenId)
    {
        try {
            $query = "SELECT * FROM vw_show_publikasi
                      WHERE dosen_id = :dosen_id
                      ORDER BY tahun_publikasi DESC, created_at DESC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':dosen_id', $dosenId, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Group by tipe_publikasi
            $groupedData = [];
            foreach ($data as $publikasi) {
                $tipe = $publikasi['tipe_publikasi'];
                if (!isset($groupedData[$tipe])) {
                    $groupedData[$tipe] = [];
                }
                $groupedData[$tipe][] = $publikasi;
            }

            return [
                'success' => true,
                'data' => $groupedData,
                'raw_data' => $data // untuk kebutuhan lain
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil publikasi: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Ambil semua publikasi dengan search, filter, dan pagination untuk halaman client
     *
     * @param array $params - Format: [
     *                          'search' => string (optional),
     *                          'tipe_publikasi' => string (optional),
     *                          'limit' => int,
     *                          'offset' => int
     *                        ]
     * @return array - Format: ['success' => bool, 'data' => array, 'total' => int]
     */
    public function getAllWithSearchAndFilter($params = [])
    {
        try {
            // Extract parameters
            $search = $params['search'] ?? '';
            $tipePublikasi = $params['tipe_publikasi'] ?? '';
            $limit = $params['limit'] ?? 10;
            $offset = $params['offset'] ?? 0;

            // Build WHERE clause
            $whereConditions = [];
            $bindParams = [];

            // Search by judul atau nama dosen
            if (!empty($search)) {
                $whereConditions[] = "(judul LIKE :search OR dosen_name LIKE :search)";
                $bindParams[':search'] = '%' . $search . '%';
            }

            // Filter by tipe publikasi
            if (!empty($tipePublikasi)) {
                $whereConditions[] = "tipe_publikasi = :tipe_publikasi";
                $bindParams[':tipe_publikasi'] = $tipePublikasi;
            }

            // Filter by tahun publikasi
            $tahunPublikasi = $params['tahun_publikasi'] ?? '';
            if (!empty($tahunPublikasi) && is_numeric($tahunPublikasi)) {
                $whereConditions[] = "tahun_publikasi = :tahun_publikasi";
                $bindParams[':tahun_publikasi'] = (int)$tahunPublikasi;
            }

            // Combine WHERE conditions
            $whereClause = '';
            if (!empty($whereConditions)) {
                $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            }

            // Count total records
            $countQuery = "SELECT COUNT(*) as total FROM vw_show_publikasi $whereClause";
            $countStmt = $this->db->prepare($countQuery);
            foreach ($bindParams as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Get data with pagination
            $query = "SELECT * FROM vw_show_publikasi
                      $whereClause
                      ORDER BY created_at DESC
                      LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);

            // Bind search and filter params
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
            return [
                'success' => false,
                'message' => 'Gagal mengambil data publikasi: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ];
        }
    }
}
