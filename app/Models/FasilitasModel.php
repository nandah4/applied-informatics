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
 * - tbl_fasilitas: Data utama fasilitas
 * 
 * Fungsi utama:
 * - insertFasilitas(): Insert data fasilitas baru (via SP)
 * - getAllFasilitas(): Ambil semua data fasilitas
 * - getAllFasilitasPaginated(): Ambil data dengan pagination
 * - getFasilitasById(): Ambil detail fasilitas berdasarkan ID
 * - updateFasilitas(): Update data fasilitas (via SP)
 * - deleteFasilitas(): Hapus fasilitas (via SP)
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
     * @return array
     */
    public function getAllFasilitas()
    {
        try {
            // ✅ Query dari VIEW (lebih simple & konsisten)
            $query = "SELECT * FROM {$this->table_name}";

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
     * GET ALL FASILITAS (PAGINATION)
     *
     * Fungsi: Mengambil data dengan batasan per halaman
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
            $query = "SELECT * FROM {$this->table_name}
                      LIMIT :limit OFFSET :offset";

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
     * @param int $id - ID fasilitas
     * @return array - ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function getFasilitasById($id)
    {
        try {
            // Query dari table utama untuk detail
            $query = "SELECT * FROM {$this->table_name} WHERE id = :id LIMIT 1";

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
     * @param array $data - Data fasilitas ['nama', 'deskripsi', 'foto']
     * @return array - ['success' => bool, 'message' => string, 'data' => ['id' => int]]
     */
    public function insertFasilitas($data)
    {
        try {
            // Gunakan CALL, bukan SELECT
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

            // Ambil ID baru yang baru saja diinsert
            // Gunakan CURRVAL untuk mendapatkan ID terakhir dari sequence
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
     * @param int $id - ID fasilitas yang akan diupdate
     * @param array $data - Data yang akan diupdate ['nama', 'deskripsi', 'foto']
     * @return array - ['success' => bool, 'message' => string]
     */
    public function updateFasilitas($id, $data)
    {
        try {
            // Gunakan CALL
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
     * @param int $id - ID fasilitas yang akan dihapus
     * @return array - ['success' => bool, 'message' => string, 'data' => ['foto' => string]]
     */
    public function deleteFasilitas($id)
    {
        try {
            // Ambil data foto SEBELUM delete
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

            // Panggil SP untuk menghapus data
            $deleteQuery = "CALL sp_delete_fasilitas(:id)";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // // ✅ FITUR BARU: Reset sequence jika tabel kosong setelah delete
            // $this->resetSequenceIfEmpty();

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

    // /**
    //  * ✅ FITUR BARU: RESET SEQUENCE IF EMPTY
    //  * 
    //  * Fungsi: Reset auto-increment sequence jika tabel kosong
    //  * Dijalankan setelah delete untuk menghindari gap ID yang terlalu besar
    //  * 
    //  * @return bool - True jika berhasil reset, false jika gagal atau tidak perlu reset
    //  */
    // private function resetSequenceIfEmpty()
    // {
    //     try {
    //         // Cek apakah tabel kosong
    //         $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name}";
    //         $countStmt = $this->db->prepare($countQuery);
    //         $countStmt->execute();
    //         $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    //         // Jika tabel kosong, reset sequence ke 1
    //         if ($total == 0) {
    //             $resetQuery = "ALTER SEQUENCE mst_fasilitas_seq RESTART WITH 1";
    //             $this->db->exec($resetQuery);
    //             error_log("FasilitasModel: Sequence direset ke 1 karena tabel kosong");
    //             return true;
    //         }

    //         // Jika masih ada data, reset sequence ke MAX(id) + 1
    //         // Ini untuk handle jika ada gap ID (misal: 1,2,5,7 -> next akan jadi 8)
    //         $maxIdQuery = "SELECT MAX(id) as max_id FROM {$this->table_name}";
    //         $maxIdStmt = $this->db->prepare($maxIdQuery);
    //         $maxIdStmt->execute();
    //         $maxId = $maxIdStmt->fetch(PDO::FETCH_ASSOC)['max_id'];

    //         if ($maxId) {
    //             $nextId = $maxId + 1;
    //             $resetQuery = "ALTER SEQUENCE mst_fasilitas_seq RESTART WITH {$nextId}";
    //             $this->db->exec($resetQuery);
    //             error_log("FasilitasModel: Sequence direset ke {$nextId}");
    //             return true;
    //         }

    //         return false;
    //     } catch (PDOException $e) {
    //         error_log("FasilitasModel resetSequenceIfEmpty error: " . $e->getMessage());
    //         return false;
    //     }
    // }
}
