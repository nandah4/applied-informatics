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
        // Auto-deactivate expired mahasiswa before fetching
        $this->autoDeactivateExpiredMahasiswa();

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
                    tipe_anggota,
                    periode_aktif,
                    tanggal_selesai,
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
        // Auto-deactivate expired mahasiswa before fetching
        $this->autoDeactivateExpiredMahasiswa();
        
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


            // Build update query dengan explicit fields
            $query = "call sp_update_mahasiswa(
                :id,
                :nim,
                :nama,
                :email,
                :no_hp,
                :semester,
                :link_github,
                :tipe_anggota,
                :periode_aktif,
                :status_aktif,
                :tanggal_selesai
            )";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nim', $data['nim']);
            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':no_hp', $data['no_hp']);
            $stmt->bindParam(':semester', $data['semester'], PDO::PARAM_INT);
            $stmt->bindParam(':link_github', $data['link_github']);
            $stmt->bindParam(':tipe_anggota', $data['tipe_anggota']);
            $stmt->bindParam(':periode_aktif', $data['periode_aktif']);
            $stmt->bindParam(':status_aktif', $data['status_aktif'], PDO::PARAM_INT);
            $stmt->bindParam(':tanggal_selesai', $data['tanggal_selesai']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data asisten lab berhasil diupdate'
            ];
        } catch (PDOException $e) {
            error_log("Error Update: " . $e->getMessage());
            $rawMessage = $e->getMessage();

            // Regex ini mencari teks setelah kata "ERROR:" sampai baris baru
            if (preg_match('/ERROR:\s+(.+?)(\n|$)/', $rawMessage, $matches)) {

                // $matches[1] otomatis berisi: "Tanggal mulai tidak boleh lebih dari tanggal akhir"
                // atau "Data mitra tidak ditemukan", sesuai apa yang dikirim SQL.
                return [
                    'success' => false,
                    'message' => $matches[1]
                ];
            }

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


    private function autoDeactivateExpiredMahasiswa()
    {
        try {
            $query = "SELECT fn_auto_deactivate_expired_mahasiswa()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            // ...
        } catch (PDOException $e) {
            error_log('Auto-deactivate expired mahasiswa failed: ' . $e->getMessage());
        }
    }
}
