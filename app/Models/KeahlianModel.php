<?php

/**
 * File: Models/KeahlianModel.php
 * Description: Handle database operations for Keahlian
 */

class KeahlianModel
{
    private $db;
    private $table_name = "ref_keahlian";
    private $table_junc = "map_dosen_keahlian";

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Tambah keahlian baru
     *
     * @param string $keahlian - Nama keahlian
     * @return array - Response dengan data {id, nama_keahlian}
     */
    public function createKeahlian($keahlian)
    {

        try {
            // Insert keahlian baru dengan RETURNING untuk mendapatkan ID
            $query = "INSERT INTO {$this->table_name}(nama_keahlian) VALUES(:keahlian) RETURNING id";
            $insertStmt = $this->db->prepare($query);
            $insertStmt->bindParam(':keahlian', $keahlian);
            $insertStmt->execute();

            $result = $insertStmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Keahlian berhasil ditambahkan',
                'data' => [
                    'id' => $result['id'],
                    'nama_keahlian' => $keahlian
                ]
            ];
        } catch (PDOException $e) {
            error_log("Keahlian Model create error: " . $e->getMessage());

            // Cek error duplicate (constraint unique)
            if (strpos($e->getMessage(), 'ref_keahlian_nama_keahlian_key') !== false) {
                return [
                    'success' => false,
                    'message' => 'Nama keahlian sudah ada'
                ];
            }

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Ambil semua data keahlian
     *
     * @return array - Response dengan data [{id, nama_keahlian}]
     */
    public function getAllKeahlian()
    {
        try {
            $query = "SELECT id, nama_keahlian FROM {$this->table_name} ORDER BY nama_keahlian ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("KeahlianModel getAll error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil data keahlian',
                'data' => []
            ];
        }
    }

    /**
     * Hapus keahlian berdasarkan ID
     *
     * @param int $id - ID keahlian
     * @return array - Response success/error
     */
    public function deleteKeahlian($id)
    {
        try {
            // Cek apakah keahlian ada
            $checkQuery = "SELECT id FROM {$this->table_name} WHERE id = :id LIMIT 1";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            if (!$checkStmt->fetch()) {
                return [
                    'success' => false,
                    'message' => 'Keahlian tidak ditemukan'
                ];
            }

            // Hapus keahlian
            $deleteQuery = "DELETE FROM {$this->table_name} WHERE id = :id";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();

            return [
                'success' => true,
                'message' => 'Keahlian berhasil dihapus'
            ];
        } catch (PDOException $e) {
            error_log("KeahlianModel delete error: " . $e->getMessage());

            // Handle foreign key constraint error (jika FK bukan CASCADE)
            if (strpos($e->getMessage(), 'foreign key') !== false ||
                strpos($e->getMessage(), 'violates') !== false) {
                return [
                    'success' => false,
                    'message' => 'Keahlian tidak bisa dihapus karena masih digunakan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghapus keahlian'
            ];
        }
    }

    /**
     * Ambil keahlian by ID Dosen
     * @param int $id
     * @return array
     */

    public function getKeahlianByDosenID($id)
    {
        try {
            $query = "SELECT k.id, k.nama_keahlian FROM $this->table_name k
            JOIN $this->table_junc tdk ON tdk.keahlian_id = k.id
            WHERE tdk.dosen_id = :p_dosen_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':p_dosen_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data keahlian by dosen id',
                'data' => $result

            ];
        } catch (PDOException $e) {
            error_log("Keahlian Model getAll error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}
