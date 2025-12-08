<?php

/**
 * File: Models/AsistenLabModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data asisten lab (mst_mahasiswa)
 *
 * Tabel: mst_mahasiswa
 * Asisten Lab adalah mahasiswa yang diterima melalui proses rekrutmen
 */

class AsistenLabModel
{
    protected $table_name = "mst_mahasiswa";

    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Get all asisten lab dengan search dan pagination
     *
     * @param array $params - Parameter search dan pagination
     *   - search: string (cari di nama, nim, email)
     *   - status_aktif: string ('all', 'aktif', 'tidak_aktif')
     *   - limit: int
     *   - offset: int
     * @return array
     */
    public function getAllWithSearchAndFilter($params = [])
    {
        try {
            $search = $params['search'] ?? '';
            $statusFilter = $params['status_aktif'] ?? 'all';
            $limit = $params['limit'] ?? 10;
            $offset = $params['offset'] ?? 0;

            // Build WHERE clause
            $whereClauses = [];
            $bindParams = [];

            // Search by nama, nim, atau email
            if (!empty($search)) {
                $whereClauses[] = "(nama ILIKE :search OR nim ILIKE :search OR email ILIKE :search)";
                $bindParams[':search'] = "%{$search}%";
            }

            // Filter by status aktif
            if ($statusFilter === 'aktif') {
                $whereClauses[] = "status_aktif = TRUE";
            } elseif ($statusFilter === 'tidak_aktif') {
                $whereClauses[] = "status_aktif = FALSE";
            }

            $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

            // Count total records
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name} {$whereSQL}";
            $countStmt = $this->db->prepare($countQuery);
            foreach ($bindParams as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Get data dengan pagination
            $query = "
                SELECT
                    id,
                    nim,
                    nama,
                    email,
                    no_hp,
                    semester,
                    jabatan_lab,
                    status_aktif,
                    tanggal_gabung,
                    created_at,
                    updated_at
                FROM {$this->table_name}
                {$whereSQL}
                ORDER BY tanggal_gabung DESC, created_at DESC
                LIMIT :limit OFFSET :offset
            ";

            $stmt = $this->db->prepare($query);

            // Bind search params
            foreach ($bindParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            // Bind pagination params
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

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
     * Get asisten lab by ID
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Data asisten lab tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update asisten lab
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        try {
            // Validasi data yang akan diupdate
            $allowedFields = ['nim', 'nama', 'email', 'no_hp', 'semester', 'link_github', 'jabatan_lab', 'status_aktif'];
            $updateFields = [];
            $bindParams = [':id' => $id];

            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateFields[] = "{$field} = :{$field}";
                    $bindParams[":{$field}"] = $data[$field];
                }
            }

            if (empty($updateFields)) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada data yang diupdate'
                ];
            }

            // Check if NIM or Email already exists for other records
            if (isset($data['nim'])) {
                $checkQuery = "SELECT id FROM {$this->table_name} WHERE nim = :nim AND id != :id";
                $checkStmt = $this->db->prepare($checkQuery);
                $checkStmt->bindParam(':nim', $data['nim']);
                $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $checkStmt->execute();

                if ($checkStmt->fetch()) {
                    return [
                        'success' => false,
                        'message' => 'NIM sudah digunakan oleh asisten lab lain'
                    ];
                }
            }

            if (isset($data['email'])) {
                $checkQuery = "SELECT id FROM {$this->table_name} WHERE email = :email AND id != :id";
                $checkStmt = $this->db->prepare($checkQuery);
                $checkStmt->bindParam(':email', $data['email']);
                $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $checkStmt->execute();

                if ($checkStmt->fetch()) {
                    return [
                        'success' => false,
                        'message' => 'Email sudah digunakan oleh asisten lab lain'
                    ];
                }
            }

            // Build and execute update query
            $updateSQL = implode(", ", $updateFields);
            $query = "UPDATE {$this->table_name} SET {$updateSQL}, updated_at = NOW() WHERE id = :id";

            $stmt = $this->db->prepare($query);

            foreach ($bindParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data asisten lab berhasil diupdate'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal update data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete asisten lab by ID
     *
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            // Check if exists
            $check = $this->getById($id);
            if (!$check['success']) {
                return [
                    'success' => false,
                    'message' => 'Data asisten lab tidak ditemukan'
                ];
            }

            // Delete from database
            $query = "DELETE FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data asisten lab berhasil dihapus'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get statistics asisten lab
     *
     * @return array
     */
    public function getStatistics()
    {
        try {
            $query = "
                SELECT
                    COUNT(*) as total,
                    COUNT(*) FILTER (WHERE status_aktif = TRUE) as aktif,
                    COUNT(*) FILTER (WHERE status_aktif = FALSE) as tidak_aktif
                FROM {$this->table_name}
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ];
        }
    }
}
