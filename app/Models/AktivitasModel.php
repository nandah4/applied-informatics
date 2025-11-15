<?php

/**
 * File: Models/AktivitasModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data aktivitas laboratorium
 *
 * Tabel yang digunakan:
 * - tbl_aktivitas_lab: Data aktivitas laboratorium
 *
 * Fungsi utama:
 * - insert(): Insert data aktivitas baru
 * - getAll(): Ambil semua data aktivitas
 * - getById(): Ambil detail aktivitas berdasarkan ID
 * - update(): Update data aktivitas
 * - delete(): Hapus aktivitas
 */

class AktivitasModel extends BaseModel
{
    protected $table_name = 'tbl_aktivitas_lab';

    /**
     * Hitung total semua data aktivitas
     * @return int
     */
    public function getTotalRecords()
    {
        return $this->count(); // Panggil count() dari BaseModel
    }

    /**
     * Ambil semua data aktivitas
     * @return array
     */
    public function getAll()
    {
        try {
            $query = "SELECT * FROM {$this->table_name} ORDER BY tanggal_kegiatan DESC, created_at DESC";
            $stmt = $this->executeQuery($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Data berhasil diambil'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data aktivitas dengan limit dan offset untuk pagination
     * @param int $limit - Jumlah data per halaman
     * @param int $offset - Data dimulai dari baris ke berapa
     * @return array
     */
    public function getAllWithLimit($limit, $offset)
    {
        try {
            $query = "SELECT * FROM {$this->table_name}
                      ORDER BY tanggal_kegiatan DESC, created_at DESC
                      LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Data berhasil diambil'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get aktivitas by ID
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->executeQuery($query, [':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return [
                    'success' => false,
                    'message' => 'Aktivitas tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Data berhasil diambil'
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
     * @param array $data
     * @return array
     */
    public function insert($data)
    {
        try {
            $query = "INSERT INTO {$this->table_name}
                      (judul_aktivitas, deskripsi, foto_aktivitas, tanggal_kegiatan)
                      VALUES
                      (:judul_aktivitas, :deskripsi, :foto_aktivitas, :tanggal_kegiatan)";

            $params = [
                ':judul_aktivitas' => $data['judul_aktivitas'],
                ':deskripsi' => $data['deskripsi'] ?? null,
                ':foto_aktivitas' => $data['foto_aktivitas'],
                ':tanggal_kegiatan' => $data['tanggal_kegiatan']
            ];

            $this->executeQuery($query, $params);

            return [
                'success' => true,
                'message' => 'Data aktivitas berhasil ditambahkan'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal menambah data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update aktivitas
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        try {
            $query = "UPDATE {$this->table_name}
                      SET judul_aktivitas = :judul_aktivitas,
                          deskripsi = :deskripsi,
                          foto_aktivitas = :foto_aktivitas,
                          tanggal_kegiatan = :tanggal_kegiatan,
                          updated_at = NOW()
                      WHERE id = :id";

            $params = [
                ':id' => $id,
                ':judul_aktivitas' => $data['judul_aktivitas'],
                ':deskripsi' => $data['deskripsi'] ?? null,
                ':foto_aktivitas' => $data['foto_aktivitas'],
                ':tanggal_kegiatan' => $data['tanggal_kegiatan']
            ];

            $this->executeQuery($query, $params);

            return [
                'success' => true,
                'message' => 'Data berhasil diupdate'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal update data: ' . $e->getMessage()
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
            $aktivitas = $this->findOne(['id' => $id]);

            if (!$aktivitas) {
                return [
                    'success' => false,
                    'message' => 'Aktivitas tidak ditemukan'
                ];
            }

            $query = "DELETE FROM {$this->table_name} WHERE id = :id";
            $this->executeQuery($query, [':id' => $id]);

            return [
                'success' => true,
                'data' => ['foto_aktivitas' => $aktivitas['foto_aktivitas']],
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
