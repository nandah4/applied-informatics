<?php

/**
 * File: Models/ContactModel.php
 * Deskripsi: Model untuk menangani operasi database terkait pesan masuk (contact us)
 */

class ContactModel
{
    protected $table_name = "trx_pesan_masuk";

    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Get all pesan dengan search dan pagination
     *
     * @param array $params - Parameter search dan pagination
     *   - search: string (cari di nama_pengirim, email_pengirim, isi_pesan)
     *   - status: string (filter by status: 'Baru' atau 'Dibalas')
     *   - limit: int
     *   - offset: int
     * @return array
     */
    public function getAllWithSearchAndFilter($params = [])
    {
        try {
            $search = $params['search'] ?? '';
            $status = $params['status'] ?? '';
            $limit = $params['limit'] ?? 10;
            $offset = $params['offset'] ?? 0;

            // Build WHERE clause
            $whereClauses = [];
            $bindParams = [];

            // Search by nama_pengirim, email_pengirim, atau isi_pesan
            if (!empty($search)) {
                $whereClauses[] = "(nama_pengirim ILIKE :search OR email_pengirim ILIKE :search OR isi_pesan ILIKE :search)";
                $bindParams[':search'] = "%{$search}%";
            }

            // Filter by status
            if (!empty($status)) {
                $whereClauses[] = "status = :status";
                $bindParams[':status'] = $status;
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
                    p.id,
                    p.nama_pengirim,
                    p.email_pengirim,
                    p.isi_pesan,
                    p.status,
                    p.dibalas_oleh,
                    p.balasan_email,
                    p.catatan_admin,
                    p.tanggal_dibalas,
                    p.created_at,
                    p.updated_at,
                    u.email as admin_email
                FROM {$this->table_name} p
                LEFT JOIN sys_users u ON p.dibalas_oleh = u.id
                {$whereSQL}
                ORDER BY 
                    CASE WHEN p.status = 'Baru' THEN 0 ELSE 1 END,
                    p.created_at DESC
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
     * Get pesan by ID
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        try {
            $query = "
                SELECT
                    p.id,
                    p.nama_pengirim,
                    p.email_pengirim,
                    p.isi_pesan,
                    p.status,
                    p.dibalas_oleh,
                    p.balasan_email,
                    p.catatan_admin,
                    p.tanggal_dibalas,
                    p.created_at,
                    p.updated_at,
                    u.email as admin_email
                FROM {$this->table_name} p
                LEFT JOIN sys_users u ON p.dibalas_oleh = u.id
                WHERE p.id = :id
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Data pesan tidak ditemukan'
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
     * Create pesan baru dari contact form (client side)
     * @param array $data
     * @return array
     */
    public function create($data)
    {
        try {
            $query = "
                INSERT INTO {$this->table_name} 
                (nama_pengirim, email_pengirim, isi_pesan)
                VALUES (:nama_pengirim, :email_pengirim, :isi_pesan)
                RETURNING id
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nama_pengirim', $data['nama_pengirim']);
            $stmt->bindParam(':email_pengirim', $data['email_pengirim']);
            $stmt->bindParam(':isi_pesan', $data['isi_pesan']);
            
            $stmt->execute();
            $newId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

            return [
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'id' => $newId
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Balas pesan (Update status + balasan)
     * @param int $id
     * @param int $admin_id
     * @param string $balasan_email - HTML content from Quill
     * @param string|null $catatan_admin - Internal note
     * @return array
     */
    public function balasPesan($id, $admin_id, $balasan_email, $catatan_admin = null)
    {
        try {
            $query = "
                UPDATE {$this->table_name}
                SET 
                    status = 'Dibalas',
                    dibalas_oleh = :admin_id,
                    balasan_email = :balasan_email,
                    catatan_admin = :catatan_admin,
                    tanggal_dibalas = NOW(),
                    updated_at = NOW()
                WHERE id = :id
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
            $stmt->bindParam(':balasan_email', $balasan_email);
            $stmt->bindParam(':catatan_admin', $catatan_admin);
            
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Pesan berhasil dibalas'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal membalas pesan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update catatan admin saja (tanpa balas email)
     * @param int $id
     * @param string $catatan_admin
     * @return array
     */
    public function updateCatatanAdmin($id, $catatan_admin)
    {
        try {
            $query = "
                UPDATE {$this->table_name}
                SET 
                    catatan_admin = :catatan_admin,
                    updated_at = NOW()
                WHERE id = :id
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':catatan_admin', $catatan_admin);
            
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Catatan admin berhasil diupdate'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal update catatan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete pesan by ID
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            $query = "DELETE FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Pesan berhasil dihapus'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus pesan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get statistik pesan (untuk dashboard)
     * @return array
     */
    public function getStatistik()
    {
        try {
            $query = "
                SELECT 
                    COUNT(*) as total_pesan,
                    COUNT(CASE WHEN status = 'Baru' THEN 1 END) as pesan_baru,
                    COUNT(CASE WHEN status = 'Dibalas' THEN 1 END) as pesan_dibalas
                FROM {$this->table_name}
            ";
            
            $stmt = $this->db->query($query);
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