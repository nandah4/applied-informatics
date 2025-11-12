<?php

/**
 * File: Models/JabatanModel.php
 * Description: Handle database operations for Jabatan
 */

class JabatanModel
{
    private $db;
    private $table_name = 'tbl_jabatan';

    function __construct()
    {
        $database = new Database;
        $this->db = $database->getConnection();
    }

    public function createJabatan($jabatan)
    {
        try {
            // Cek apakah jabatan sudah ada
            $query = "SELECT id FROM {$this->table_name} WHERE LOWER(jabatan) = LOWER(:jabatan) LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':jabatan', $jabatan);
            $stmt->execute();

            // Jika SUDAH ada (fetch berhasil), return error
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                return [
                    'success' => false,
                    'message' => 'Jabatan sudah ada dalam database'
                ];
            }

            // Insert jabatan baru dengan RETURNING untuk mendapatkan ID
            $insertQuery = "INSERT INTO {$this->table_name}(jabatan) VALUES(:jabatan) RETURNING id";
            $insertStmt = $this->db->prepare($insertQuery);
            $insertStmt->bindParam(':jabatan', $jabatan);
            $insertStmt->execute();

            $result = $insertStmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'message' => 'Jabatan berhasil ditambahkan',
                'data' => [
                    'id' => $result['id'],
                    'jabatan' => $jabatan
                ]
            ];
        } catch (PDOException $e) {
            error_log("Jabatan Model create error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }

    public function getAllJabatan()
    {
        try {
            $query = "SELECT * FROM $this->table_name ORDER BY jabatan ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan data jabatan',
                'data' => $result

            ];

        } catch (PDOException $e) {
            error_log("Jabatan Model getAll error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Delete jabatan by ID
     * @param int $id
     * @return array
     */
    public function deleteJabatan($id)
    {
        try {
            // Check if jabatan exists
            $checkQuery = "SELECT jabatan FROM {$this->table_name} WHERE id = :id LIMIT 1";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            $jabatan = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$jabatan) {
                return [
                    'success' => false,
                    'message' => 'Jabatan tidak ditemukan'
                ];
            }

            // Delete jabatan
            $deleteQuery = "DELETE FROM {$this->table_name} WHERE id = :id";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();

            return [
                'success' => true,
                'message' => 'Jabatan berhasil dihapus'
            ];
        } catch (PDOException $e) {
            error_log("JabatanModel delete error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ];
        }
    }
}
