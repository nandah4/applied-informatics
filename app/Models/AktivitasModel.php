<?php

/**
 * File: Models/AktivitasModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data aktivitas laboratorium
 *
 * Tabel/View yang digunakan:
 * - trx_aktivitas_lab: Data aktivitas laboratorium
 * - vw_show_aktivitas_lab: View untuk menampilkan data
 *
 * Stored Procedures:
 * - sp_insert_aktivitas_lab: Insert aktivitas baru
 * - sp_update_aktivitas_lab: Update aktivitas
 *
 * Fungsi utama:
 * - insert(): Insert data aktivitas baru
 * - getAllWithPagination(): Ambil data dengan pagination
 * - getById(): Ambil detail aktivitas berdasarkan ID
 * - update(): Update data aktivitas
 * - delete(): Hapus aktivitas
 */

class AktivitasModel extends BaseModel
{
    protected $table_name = 'trx_aktivitas_lab';

    /**
     * Ambil semua data aktivitas tanpa pagination
     *
     * @return array
     */

    public function getAll($limit = 6)
    {
        try {
            $query = "SELECT id, judul_aktivitas, foto_aktivitas, deskripsi, tanggal_kegiatan FROM {$this->table_name} ORDER BY tanggal_kegiatan DESC, created_at DESC LIMIT :limit";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "success" => true,
                "data" => $result
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'data' => [],
            ];
        }
    }

    /**
     * Ambil semua data aktivitas dengan pagination
     *
     * @param int $limit - Jumlah data per halaman
     * @param int $offset - Data dimulai dari baris ke berapa
     * @return array
     */
    public function getAllWithPagination($params = [])
    {
        try {

            $search = $params['search'] ?? '';
            $limit = $params['limit'] ?? 10;
            $offset = $params['offset'] ?? 0;

            $whereClauses = [];
            $bindParams = [];

            if (!empty($search)) {
                $whereClauses[] = "(judul_aktivitas ILIKE :search)";
                $bindParams[':search'] = "%{$search}%";
            }

            $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

            // Hitung total records
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name} {$whereSQL}";
            $countStmt = $this->db->prepare($countQuery);

            foreach ($bindParams as $key => $value) {
                $countStmt->bindValue($key, $value);
            }

            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Ambil data dengan pagination
            $query = "SELECT
                        id,
                        judul_aktivitas,
                        deskripsi,
                        foto_aktivitas,
                        tanggal_kegiatan,
                        created_at,
                        updated_at
                    FROM {$this->table_name}
                    {$whereSQL}
                    ORDER BY tanggal_kegiatan DESC, created_at DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            
            // Bind search params
            foreach ($bindParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }

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
     * Get aktivitas by ID
     * Menggunakan view vw_show_aktivitas_lab
     *
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        try {
            $query = "SELECT
                        id,
                        judul_aktivitas,
                        deskripsi,
                        foto_aktivitas,
                        tanggal_kegiatan,
                        created_at,
                        updated_at
                    FROM {$this->table_name}
                    WHERE id = :id
                    ORDER BY tanggal_kegiatan DESC, created_at DESC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return [
                    'success' => false,
                    'message' => 'Aktivitas tidak ditemukan'
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
     * Insert new aktivitas
     * Menggunakan stored procedure sp_insert_aktivitas_lab
     *
     * @param array $data
     * @return array
     */
    public function insert($data)
    {
        try {
            $query = "CALL sp_insert_aktivitas_lab(:judul_aktivitas, :deskripsi, :foto_aktivitas, :tanggal_kegiatan)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':judul_aktivitas', $data['judul_aktivitas']);
            $stmt->bindParam(':deskripsi', $data['deskripsi']);
            $stmt->bindParam(':foto_aktivitas', $data['foto_aktivitas']);
            $stmt->bindParam(':tanggal_kegiatan', $data['tanggal_kegiatan']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data aktivitas berhasil ditambahkan'
            ];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            // Handle error dari stored procedure
            if (strpos($errorMessage, 'Judul aktivitas tidak boleh kosong') !== false) {
                return [
                    'success' => false,
                    'message' => 'Judul aktivitas tidak boleh kosong'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menambah data: ' . $errorMessage
            ];
        }
    }

    /**
     * Update aktivitas
     * Menggunakan stored procedure sp_update_aktivitas_lab
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        try {
            $query = "CALL sp_update_aktivitas_lab(:id, :judul_aktivitas, :deskripsi, :foto_aktivitas, :tanggal_kegiatan)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':judul_aktivitas', $data['judul_aktivitas']);
            $stmt->bindParam(':deskripsi', $data['deskripsi']);
            $stmt->bindParam(':foto_aktivitas', $data['foto_aktivitas']);
            $stmt->bindParam(':tanggal_kegiatan', $data['tanggal_kegiatan']);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Data berhasil diupdate'
            ];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            // Handle error dari stored procedure
            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return [
                    'success' => false,
                    'message' => 'Aktivitas tidak ditemukan'
                ];
            }

            if (strpos($errorMessage, 'Judul aktivitas tidak boleh kosong') !== false) {
                return [
                    'success' => false,
                    'message' => 'Judul aktivitas tidak boleh kosong'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal update data: ' . $errorMessage
            ];
        }
    }

    /**
     * Delete aktivitas
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            // Get aktivitas data first (untuk return foto path)
            $aktivitas = $this->getById($id);

            if (!$aktivitas || !$aktivitas['success']) {
                return [
                    'success' => false,
                    'message' => 'Aktivitas tidak ditemukan'
                ];
            }

            $query = "DELETE FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'success' => true,
                'data' => ['foto_aktivitas' => $aktivitas['data']['foto_aktivitas'] ?? null],
                'message' => 'Data berhasil dihapus'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }
}
