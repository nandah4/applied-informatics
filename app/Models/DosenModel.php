<?php

/**
 * File: Models/DosenModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data dosen
 *
 * Tabel yang digunakan:
 * - tbl_dosen: Data utama dosen
 * - tbl_dosen_keahlian: Junction table untuk relasi many-to-many dosen-keahlian
 * - tbl_jabatan: Data jabatan dosen
 * - tbl_keahlian: Data keahlian dosen
 *
 * Fungsi utama:
 * - insertDosen(): Insert data dosen baru
 * - insertDosenKeahlian(): Insert keahlian dosen (junction table)
 * - getAllDosen(): Ambil semua data dosen dengan JOIN
 * - getDosenById(): Ambil detail dosen berdasarkan ID
 * - updateDosen(): Update data dosen
 * - deleteDosen(): Hapus dosen
 */

class DosenModel
{
    private $db;
    private $table_name = 'tbl_dosen';

    /**
     * Constructor: Inisialisasi koneksi database
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

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
     * @return array - Format: ['success' => bool, 'message' => string, 'data' => ['id' => int]]
     */
    public function insertDosen($data)
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
            $query = "CALL sp_insert_dosen_with_keahlian(
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
            $stmt->bindParam(':keahlian_ids', $keahlianIds, PDO::PARAM_STR); // Array sebagai string
            $stmt->bindParam(':foto_profil', $data['foto_profil'], PDO::PARAM_STR);
            $stmt->bindParam(':deskripsi', $data['deskripsi'], PDO::PARAM_STR);

            // Execute procedure
            $stmt->execute();

            // Jika berhasil (tidak ada exception), ambil ID dosen yang baru dibuat
            // Karena procedure tidak return value, maka query manual
            $lastIdQuery = "SELECT id FROM tbl_dosen WHERE email = :email ORDER BY id DESC LIMIT 1";
            $lastIdStmt = $this->db->prepare($lastIdQuery);
            $lastIdStmt->bindParam(':email', $data['email']);
            $lastIdStmt->execute();
            $result = $lastIdStmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Data dosen berhasil ditambahkan',
                'data' => [
                    'id' => $result['id'] ?? null
                ]
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

            // // Error dari database constraint
            // if (strpos($errorMessage, 'tbl_dosen_email_key') !== false) {
            //     return [
            //         'success' => false,
            //         'message' => 'Email sudah terdaftar dalam sistem'
            //     ];
            // }

            // if (strpos($errorMessage, 'tbl_dosen_nidn_key') !== false) {
            //     return [
            //         'success' => false,
            //         'message' => 'NIDN sudah terdaftar dalam sistem'
            //     ];
            // }

            // Error lainnya
            return [
                'success' => false,
                'message' => 'Gagal menambahkan data dosen: ' . $errorMessage
            ];
        }
    }

    /**
     * Insert keahlian dosen ke junction table (tbl_dosen_keahlian)
     * Mendukung multiple keahlian untuk satu dosen
     *
     * @param int $dosen_id - ID dosen
     * @param array $keahlian_ids - Array of keahlian IDs, contoh: [1, 3, 5]
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function insertDosenKeahlian($dosen_id, $keahlian_ids)
    {
        try {
            // Mulai transaction untuk memastikan atomicity
            // Semua insert berhasil atau semua rollback
            $this->db->beginTransaction();

            $query = "INSERT INTO tbl_dosen_keahlian (dosen_id, keahlian_id)
                      VALUES (:dosen_id, :keahlian_id)";
            $stmt = $this->db->prepare($query);

            // Loop dan insert setiap keahlian
            foreach ($keahlian_ids as $keahlian_id) {
                $stmt->bindParam(':dosen_id', $dosen_id, PDO::PARAM_INT);
                $stmt->bindParam(':keahlian_id', $keahlian_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Commit transaction jika semua berhasil
            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Keahlian dosen berhasil ditambahkan'
            ];
        } catch (PDOException $e) {
            // Rollback jika ada error
            $this->db->rollBack();

            error_log("DosenModel insertDosenKeahlian error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal menambahkan keahlian dosen'
            ];
        }
    }

    /**
     * Ambil semua data dosen dengan JOIN ke tabel terkait
     *
     * Query ini akan mengambil:
     * - Data dosen (dari tbl_dosen)
     * - Nama jabatan (dari tbl_jabatan)
     * - List keahlian dalam satu string (dari tbl_keahlian via junction table)
     *
     * @return array - Format: ['success' => bool, 'message' => string, 'data' => array]
     */
    public function getAllDosen()
    {
        try {
            // Query dengan JOIN dan STRING_AGG untuk menggabungkan keahlian
            $query = "SELECT
                        d.id,
                        d.full_name,
                        d.email,
                        d.nidn,
                        d.foto_profil,
                        d.deskripsi,
                        d.jabatan_id,
                        j.jabatan as jabatan_name,
                        STRING_AGG(k.keahlian, ', ') as keahlian_list
                      FROM {$this->table_name} d
                      LEFT JOIN tbl_jabatan j ON d.jabatan_id = j.id
                      LEFT JOIN tbl_dosen_keahlian dk ON d.id = dk.dosen_id
                      LEFT JOIN tbl_keahlian k ON dk.keahlian_id = k.id
                      GROUP BY d.id, d.full_name, d.email, d.nidn, d.foto_profil, d.deskripsi, d.jabatan_id, j.jabatan
                      ORDER BY d.id DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data dosen',
                'data' => $result
            ];
        } catch (PDOException $e) {
            error_log("DosenModel getAllDosen error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan data dosen',
                'data' => []
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
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name}";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Query dengan pagination
            $query = "SELECT
                      d.id,
                      d.full_name,
                      d.email,
                      d.nidn,
                      d.foto_profil,
                      d.deskripsi,
                      d.jabatan_id,
                      j.jabatan as jabatan_name,
                      STRING_AGG(k.keahlian, ', ') as keahlian_list
                    FROM {$this->table_name} d
                    LEFT JOIN tbl_jabatan j ON d.jabatan_id = j.id
                    LEFT JOIN tbl_dosen_keahlian dk ON d.id = dk.dosen_id
                    LEFT JOIN tbl_keahlian k ON dk.keahlian_id = k.id
                    GROUP BY d.id, d.full_name, d.email, d.nidn, d.foto_profil, d.deskripsi, d.jabatan_id, j.jabatan
                    ORDER BY d.id DESC
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data dosen',
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
            $query = "SELECT
                        d.id,
                        d.full_name,
                        d.email,
                        d.nidn,
                        d.foto_profil,
                        d.deskripsi,
                        d.jabatan_id,
                        j.jabatan as jabatan_name
                      FROM {$this->table_name} d
                      LEFT JOIN tbl_jabatan j ON d.jabatan_id = j.id
                      WHERE d.id = :id
                      LIMIT 1";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

            // Jika dosen tidak ditemukan
            if (!$dosen) {
                return [
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan',
                    'data' => null
                ];
            }

            // Ambil keahlian dosen
            $queryKeahlian = "SELECT k.id, k.keahlian
                              FROM tbl_keahlian k
                              INNER JOIN tbl_dosen_keahlian dk ON k.id = dk.keahlian_id
                              WHERE dk.dosen_id = :id
                              ORDER BY k.keahlian ASC";

            $stmtKeahlian = $this->db->prepare($queryKeahlian);
            $stmtKeahlian->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtKeahlian->execute();

            $dosen['keahlian'] = $stmtKeahlian->fetchAll(PDO::FETCH_ASSOC);

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
    public function updateDosen($id, $data)
    {
        try {
            $query = "UPDATE {$this->table_name}
                      SET full_name = :full_name,
                          email = :email,
                          nidn = :nidn,
                          jabatan_id = :jabatan_id,
                          foto_profil = :foto_profil,
                          deskripsi = :deskripsi
                      WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':nidn', $data['nidn']);
            $stmt->bindParam(':jabatan_id', $data['jabatan_id'], PDO::PARAM_INT);
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
            if (strpos($e->getMessage(), 'tbl_dosen_email_key') !== false) {
                return [
                    'success' => false,
                    'message' => 'Email sudah terdaftar dalam sistem'
                ];
            }

            if (strpos($e->getMessage(), 'tbl_dosen_nidn_key') !== false) {
                return [
                    'success' => false,
                    'message' => 'NIDN sudah terdaftar dalam sistem'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate data dosen'
            ];
        }
    }

    /**
     * Update keahlian dosen
     * Metode: Hapus semua keahlian lama, lalu insert yang baru
     *
     * @param int $dosen_id - ID dosen
     * @param array $keahlian_ids - Array of keahlian IDs baru
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function updateDosenKeahlian($dosen_id, $keahlian_ids)
    {
        try {
            $this->db->beginTransaction();

            // 1. Hapus semua keahlian lama
            $deleteQuery = "DELETE FROM tbl_dosen_keahlian WHERE dosen_id = :dosen_id";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':dosen_id', $dosen_id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // 2. Insert keahlian baru
            if (!empty($keahlian_ids)) {
                $insertQuery = "INSERT INTO tbl_dosen_keahlian (dosen_id, keahlian_id)
                                VALUES (:dosen_id, :keahlian_id)";
                $insertStmt = $this->db->prepare($insertQuery);

                foreach ($keahlian_ids as $keahlian_id) {
                    $insertStmt->bindParam(':dosen_id', $dosen_id, PDO::PARAM_INT);
                    $insertStmt->bindParam(':keahlian_id', $keahlian_id, PDO::PARAM_INT);
                    $insertStmt->execute();
                }
            }

            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Keahlian dosen berhasil diupdate'
            ];
        } catch (PDOException $e) {
            $this->db->rollBack();

            error_log("DosenModel updateDosenKeahlian error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengupdate keahlian dosen'
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
    public function deleteDosen($id)
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

    /**
     * Cek apakah sudah ada dosen dengan jabatan tertentu
     * Digunakan untuk validasi jabatan yang bersifat unik (contoh: Ketua Lab)
     *
     * @param int $jabatan_id - ID jabatan yang akan dicek
     * @param int|null $exclude_dosen_id - ID dosen yang akan dikecualikan dari pengecekan (untuk update)
     * @return array - Format: ['exists' => bool, 'dosen_name' => string|null, 'jabatan_name' => string|null]
     */
    public function isJabatanExists($jabatan_id, $exclude_dosen_id = null)
    {
        try {
            // Query mencari dosen lain dengan jabatan yang sama
            $query = "
            SELECT d.id AS dosen_id, d.full_name, j.jabatan
            FROM {$this->table_name} d
            INNER JOIN tbl_jabatan j ON d.jabatan_id = j.id
            WHERE d.jabatan_id = :jabatan_id
        ";

            // Jika exclude diberikan (angka > 0), tambahkan kondisi exclude
            if ($exclude_dosen_id !== null && (int)$exclude_dosen_id > 0) {
                $query .= " AND d.id <> :exclude_id";
            }

            $query .= " LIMIT 1";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':jabatan_id', (int)$jabatan_id, PDO::PARAM_INT);

            if ($exclude_dosen_id !== null && (int)$exclude_dosen_id > 0) {
                // bindValue aman untuk nilai literal
                $stmt->bindValue(':exclude_id', (int)$exclude_dosen_id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return [
                    'exists' => true,
                    'dosen_id' => (int)$result['dosen_id'],
                    'dosen_name' => $result['full_name'],
                    'jabatan_name' => $result['jabatan']
                ];
            }

            return [
                'exists' => false,
                'dosen_id' => null,
                'dosen_name' => null,
                'jabatan_name' => null
            ];
        } catch (PDOException $e) {
            error_log("DosenModel isJabatanExists error: " . $e->getMessage());
            return [
                'exists' => false,
                'dosen_id' => null,
                'dosen_name' => null,
                'jabatan_name' => null
            ];
        }
    }


    /**
     * Ambil nama jabatan berdasarkan ID
     *
     * @param int $jabatan_id - ID jabatan
     * @return string|null - Nama jabatan atau null jika tidak ditemukan
     */
    public function getJabatanNameById($jabatan_id)
    {
        try {
            $query = "SELECT jabatan FROM tbl_jabatan WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $jabatan_id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['jabatan'] : null;
        } catch (PDOException $e) {
            error_log("DosenModel getJabatanNameById error: " . $e->getMessage());
            return null;
        }
    }
}
