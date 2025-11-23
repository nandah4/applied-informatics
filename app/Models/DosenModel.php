<?php

/**
 * File: Models/DosenModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data dosen
 *
 * Tabel yang digunakan:
 * - mst_dosen: Data utama dosen
 * - tbl_dosen_keahlian: Junction table untuk relasi many-to-many dosen-keahlian
 * - tbl_jabatan: Data jabatan dosen
 * - tbl_keahlian: Data keahlian dosen
 *
 * Fungsi utama:
 * - insert(): Insert data dosen baru menggunakan stored procedure
 * - getAllDosen(): Ambil semua data dosen dengan JOIN
 * - getDosenById(): Ambil detail dosen berdasarkan ID
 * - updateDosen(): Update data dosen
 * - deleteDosen(): Hapus dosen
 */

class DosenModel extends BaseModel
{
    protected $table_name = 'mst_dosen';

    /**
     * Insert data dosen baru ke database menggunakan stored procedure
     *
     * @param array $data - Data dosen yang akan diinsert
     *                      Format: [
     *                          'full_name' => string,
     *                          'email' => string,
     *                          'nidn' => string,
     *                          'jabatan_id' => int,
     *                          'keahlian_ids' => array, // [1, 2, 3]
     *                          'foto_profil' => string|null,
     *                          'deskripsi' => string|null
     *                      ]
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function insert($data)
    {
        try {
            // Siapkan array keahlian_ids untuk PostgreSQL
            // Jika keahlian_ids sudah array: [1, 2, 3] -> '{1,2,3}'
            // Jika keahlian_ids string: "1,2,3" -> '{1,2,3}'
            $keahlianIds = null;
            if (isset($data['keahlian_ids'])) {
                if (is_array($data['keahlian_ids'])) {
                    $keahlianIds = '{' . implode(',', $data['keahlian_ids']) . '}';
                } else {
                    // Jika string, pastikan formatnya benar
                    $keahlianIds = '{' . $data['keahlian_ids'] . '}';
                }
            }

            // Query CALL stored procedure
            // Format: CALL sp_name(param1, param2, ...)
            $query = "CALL sp_insert_dosen(
                :full_name,
                :email,
                :nidn,
                :jabatan_id,
                :keahlian_ids,
                :foto_profil,
                :deskripsi
            )";

            $stmt = $this->db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':nidn', $data['nidn'], PDO::PARAM_STR);
            $stmt->bindParam(':jabatan_id', $data['jabatan_id'], PDO::PARAM_INT);
            $stmt->bindParam(':keahlian_ids', $keahlianIds, PDO::PARAM_STR);
            $stmt->bindParam(':foto_profil', $data['foto_profil'], PDO::PARAM_STR);
            $stmt->bindParam(':deskripsi', $data['deskripsi'], PDO::PARAM_STR);

            // Execute procedure
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data dosen berhasil ditambahkan',
            ];
        } catch (PDOException $e) {
            // Log error untuk debugging
            error_log("DosenModel insertDosen error: " . $e->getMessage());

            // Cek apakah error dari RAISE EXCEPTION di procedure
            $errorMessage = $e->getMessage();

            // Error dari procedure (RAISE EXCEPTION)
            if (strpos($errorMessage, 'Email sudah terdaftar') !== false) {
                return [
                    'success' => false,
                    'message' => 'Email sudah terdaftar dalam sistem'
                ];
            }

            if (strpos($errorMessage, 'NIDN sudah terdaftar') !== false) {
                return [
                    'success' => false,
                    'message' => 'NIDN sudah terdaftar dalam sistem'
                ];
            }

            // Error lainnya
            return [
                'success' => false,
                'message' => 'Gagal menambahkan data dosen: ' . $errorMessage
            ];
        }
    }

    /**
     * Ambil data dosen dengan pagination
     *
     * @param int $limit - Jumlah data per halaman
     * @param int $offset - Offset untuk query
     * @return array - Format: ['success' => bool, 'data' => array, 'total' => int]
     */
    public function getAllDosenPaginated($limit = 10, $offset = 0)
    {
        try {
            // Query untuk hitung total records
            $countQuery = "SELECT COUNT(*) as total FROM vw_show_dosen";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Query dengan pagination
            $query = "SELECT * FROM vw_show_dosen
                    ORDER BY created_at DESC
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
            error_log("DosenModel getAllDosenPaginated error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data dosen',
                'data' => [],
                'total' => 0
            ];
        }
    }


    /**
     * Ambil detail dosen berdasarkan ID
     *
     * @param int $id - ID dosen
     * @return array - Format: ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function getDosenById($id)
    {
        try {
            // Query untuk ambil data dosen beserta jabatan
            $query = "SELECT * FROM vw_show_dosen
                      WHERE id = :id
                      LIMIT 1";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dosen) {
                return [
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan',
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan detail dosen',
                'data' => $dosen
            ];
        } catch (PDOException $e) {
            error_log("DosenModel getDosenById error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan detail dosen',
                'data' => null
            ];
        }
    }

    /**
     * Update data dosen
     *
     * @param int $id - ID dosen yang akan diupdate
     * @param array $data - Data yang akan diupdate
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function update($id, $data)
    {
        try {
           $query = "CALL sp_update_dosen(
                :id,
                :full_name,
                :email,
                :nidn,
                :jabatan_id,
                :keahlian_ids,
                :foto_profil,
                :deskripsi
            )";

            $stmt = $this->db->prepare($query);

            // Konversi keahlian_ids ke format PostgreSQL array
            $keahlianIds = '{}'; // Default empty array
            if (!empty($data['keahlian_ids'])) {
                if (is_array($data['keahlian_ids'])) {
                    $keahlianIds = '{' . implode(',', $data['keahlian_ids']) . '}';
                } else {
                    $keahlianIds = '{' . $data['keahlian_ids'] . '}';
                }
            }

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':nidn', $data['nidn']);
            $stmt->bindParam(':jabatan_id', $data['jabatan_id'], PDO::PARAM_INT);
            $stmt->bindParam(':keahlian_ids', $keahlianIds);
            $stmt->bindParam(':foto_profil', $data['foto_profil']);
            $stmt->bindParam(':deskripsi', $data['deskripsi']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data dosen berhasil diupdate'
            ];
        } catch (PDOException $e) {
            error_log("DosenModel updateDosen error: " . $e->getMessage());

            // Cek duplicate constraints
            if (strpos($e->getMessage(), 'mst_dosen_email_key') !== false) {
                return [
                    'success' => false,
                    'message' => 'Email sudah terdaftar dalam sistem'
                ];
            }

            if (strpos($e->getMessage(), 'mst_dosen_nidn_key') !== false) {
                return [
                    'success' => false,
                    'message' => 'NIDN sudah terdaftar dalam sistem'
                ];
            }

            return [
                'success' => false,
                'message' =>  "Gagal mengupdate dosen!"
            ];
        }
    }

    /**
     * Hapus dosen dari database
     * Keahlian dosen akan dihapus otomatis karena CASCADE di foreign key
     *
     * @param int $id - ID dosen yang akan dihapus
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function delete($id)
    {
        try {
            // Cek apakah dosen exists
            $checkQuery = "SELECT full_name, foto_profil FROM {$this->table_name} WHERE id = :id LIMIT 1";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            $dosen = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$dosen) {
                return [
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan'
                ];
            }

            // Hapus dosen dari database
            $deleteQuery = "DELETE FROM {$this->table_name} WHERE id = :id";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();

            return [
                'success' => true,
                'message' => 'Data dosen berhasil dihapus',
                'data' => [
                    'foto_profil' => $dosen['foto_profil'] // Return untuk hapus file foto
                ]
            ];
        } catch (PDOException $e) {
            error_log("DosenModel deleteDosen error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal menghapus data dosen'
            ];
        }
    }
}



/**
 * ============================
 *  TRASH CODE
 * ============================
 */

/**
 * Ambil semua data dosen dengan JOIN ke tabel terkait
 *
 * Query ini akan mengambil:
 * - Data dosen (dari mst_dosen)
 * - Nama jabatan (dari ref_jabatan)
 * - List keahlian dalam satu string (dari ref_keahlian via junction table)
 *
 * @return array - Format: ['success' => bool, 'message' => string, 'data' => array]
 */
    // public function getAll()
    // {
    //     try {
    //         $query = "SELECT * FROM vw_show_dosen";

    //         $stmt = $this->db->prepare($query);
    //         $stmt->execute();

    //         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //         return [
    //             'success' => true,
    //             'data' => $result
    //         ];
    //     } catch (PDOException $e) {
    //         error_log("DosenModel getAllDosen error: " . $e->getMessage());
    //         return [
    //             'success' => false,
    //             'message' => 'Gagal mendapatkan data dosen',
    //             'data' => []
    //         ];
    //     }
    // }