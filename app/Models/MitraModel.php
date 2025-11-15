<?php

/**
 * File: Models/MitraModel.php
 * Deskripsi: Model untuk menangani operasi database terkait data mitra
 *
 * Tabel yang digunakan:
 * - tbl_mitra: Data utama mitra kerjasama
 *
 * Fungsi utama:
 * - insert(): Insert data mitra baru
 * - getAll(): Ambil semua data mitra
 * - getById(): Ambil detail mitra berdasarkan ID
 * - update(): Update data mitra
 * - delete(): Hapus mitra
 */

class MitraModel extends BaseModel
{
    protected $table_name = 'tbl_mitra';

    /**
     * Hitung total semua data mitra
     * @return int
     */
    public function getTotalRecords()
    {
        return $this->count(); // Panggil count() dari BaseModel
    }

    /**
     * Ambil semua data mitra
     * @return array
     */
    public function getAll()
    {
        try {
            $query = "SELECT * FROM {$this->table_name} ORDER BY created_at DESC";
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
     * Ambil data mitra dengan limit dan offset untuk pagination
     * @param int $limit - Jumlah data per halaman
     * @param int $offset - Data dimulai dari baris ke berapa
     * @return array
     */
    public function getAllWithLimit($limit, $offset)
    {
        try {
            $query = "SELECT * FROM {$this->table_name}
                      ORDER BY created_at DESC
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
     * Get mitra by ID
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
                    'message' => 'Mitra tidak ditemukan'
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
     * Insert new mitra
     * @param array $data
     * @return array
     */
    public function insert($data)
    {
        try {
            $query = "INSERT INTO {$this->table_name}
                      (nama, status, kategori_mitra, logo_mitra, tanggal_mulai, tanggal_akhir, deskripsi)
                      VALUES
                      (:nama, :status, :kategori_mitra, :logo_mitra, :tanggal_mulai, :tanggal_akhir, :deskripsi)";

            $params = [
                ':nama' => $data['nama'],
                ':status' => $data['status'],
                ':kategori_mitra' => $data['kategori_mitra'] ?? 'industri',
                ':logo_mitra' => $data['logo_mitra'],
                ':tanggal_mulai' => $data['tanggal_mulai'],
                ':tanggal_akhir' => $data['tanggal_akhir'] ?? null,
                ':deskripsi' => $data['deskripsi'] ?? null
            ];

            $this->executeQuery($query, $params);

            // // Get last insert ID
            // $lastId = $this->db->lastInsertId();

            return [
                'success' => true,
                'message' => 'Data mitra berhasil ditambahkan'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal menambah data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update mitra
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        try {
            $query = "UPDATE {$this->table_name}
                      SET nama = :nama,
                          status = :status,
                          kategori_mitra = :kategori_mitra,
                          logo_mitra = :logo_mitra,
                          tanggal_mulai = :tanggal_mulai,
                          tanggal_akhir = :tanggal_akhir,
                          deskripsi = :deskripsi,
                          updated_at = NOW()
                      WHERE id = :id";

            $params = [
                ':id' => $id,
                ':nama' => $data['nama'],
                ':status' => $data['status'],
                ':kategori_mitra' => $data['kategori_mitra'] ?? 'industri',
                ':logo_mitra' => $data['logo_mitra'],
                ':tanggal_mulai' => $data['tanggal_mulai'],
                ':tanggal_akhir' => $data['tanggal_akhir'] ?? null,
                ':deskripsi' => $data['deskripsi'] ?? null
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
     * Delete mitra
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            // Get mitra data first (untuk return logo path)
            $mitra = $this->findOne(['id' => $id]);

            if (!$mitra) {
                return [
                    'success' => false,
                    'message' => 'Mitra tidak ditemukan'
                ];
            }

            $query = "DELETE FROM {$this->table_name} WHERE id = :id";
            $this->executeQuery($query, [':id' => $id]);

            return [
                'success' => true,
                'data' => ['logo_mitra' => $mitra['logo_mitra']],
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
