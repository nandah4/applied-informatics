<?php

/**
 * File: Models/KeahlianModel.php
 * Description: Handle database operations for Keahlian.php
 */

class KeahlianModel
{
    private $db;
    private $table_name = "tbl_keahlian";
    private $table_dosen = "tbl_dosen";
    private $table_junc = "tbl_dosen_keahlian";

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // POST : membuat keahlian baru
    public function createKeahlian($keahlian)
    {

        try {
            // Cek apakah jabatan sudah ada
            $checkQuery = "SELECT id FROM {$this->table_name} WHERE keahlian = :keahlian LIMIT 1";
            $stmt = $this->db->prepare($checkQuery);
            $stmt->bindParam(":keahlian", $keahlian);
            $stmt->execute();

            // Jika SUDAH ada (fetch berhasil), return error
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                return [
                    'success' => false,
                    'message' => 'Keahlian sudah ada dalam database'
                ];
            }

            // Insert keahlian baru dengan RETURNING untuk mendapatkan ID
            $query = "INSERT INTO {$this->table_name}(keahlian) VALUES(:keahlian) RETURNING id";
            $insertStmt = $this->db->prepare($query);
            $insertStmt->bindParam(':keahlian', $keahlian);
            $insertStmt->execute();

            $result = $insertStmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Keahlian berhasil ditambahkan',
                'data' => [
                    'id' => $result['id'],
                    'keahlian' => $keahlian
                ]
            ];
        } catch (PDOException $e) {
            error_log("Keahlian Model create error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }

    // GET : mendapatkan semua data keahlian
    public function getAllKeahlian()
    {
        try {
            $query = "SELECT * FROM {$this->table_name} ORDER BY keahlian ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data keahlian',
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

    /**
     * Delete keahlian by ID
     * @param int $id
     * @return array
     */
    public function deleteKeahlian($id)
    {
        try {
            // Check if keahlian exists
            $checkQuery = "SELECT keahlian FROM {$this->table_name} WHERE id = :id LIMIT 1";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            $keahlian = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$keahlian) {
                return [
                    'success' => false,
                    'message' => 'Keahlian tidak ditemukan'
                ];
            }

            // Delete keahlian
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
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get keahlian by ID Dosen
     * @param int $id
     * @return array
     */

    public function getKeahlianByDosenId($id)
    {
        try {
            $query = "SELECT k.id, k.keahlian FROM $this->table_name k
            JOIN $this->table_junc tdk ON tdk.keahlian_id = k.id
            WHERE tdk.dosen_id = :p_dosen_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':p_dosen_id', $id);
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
