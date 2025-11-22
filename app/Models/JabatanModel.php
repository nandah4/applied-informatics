<?php

/**
 * File: Models/JabatanModel.php
 * Deskripsi: Model untuk operasi database tabel jabatan
 *
 * Tabel: ref_jabatan
 * Kolom: id, nama_jabatan
 *
 * Fungsi:
 * - createJabatan(): Insert jabatan baru
 * - getAllJabatan(): Ambil semua jabatan
 * - deleteJabatan(): Hapus jabatan by ID
 */

class JabatanModel
{
    private $db;
    private $table_name = 'ref_jabatan';

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Tambah jabatan baru
     *
     * @param string $jabatan - Nama jabatan
     * @return array - Response dengan data {id, nama_jabatan}
     */
    public function createJabatan($jabatan)
    {
        try {
            $query = "INSERT INTO {$this->table_name}(nama_jabatan) VALUES(:jabatan)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':jabatan', $jabatan, PDO::PARAM_STR);
            $stmt->execute();

            $newId = $this->db->lastInsertId();

            return [
                'success' => true,
                'message' => 'Jabatan berhasil ditambahkan',
                'data' => [
                    'id' => $newId,
                    'nama_jabatan' => $jabatan
                ]
            ];
        } catch (PDOException $e) {
            error_log("JabatanModel create error: " . $e->getMessage());

            // Cek error duplicate (constraint unique)
            if (strpos($e->getMessage(), 'ref_jabatan_nama_jabatan_key') !== false) {
                return [
                    'success' => false,
                    'message' => 'Nama jabatan sudah ada'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menambahkan jabatan'
            ];
        }
    }

    /**
     * Ambil semua data jabatan
     *
     * @return array - Response dengan data [{id, jabatan}]
     */
    public function getAllJabatan()
    {
        try {
            // Alias nama_jabatan as jabatan untuk konsistensi dengan frontend
            $query = "SELECT id, nama_jabatan FROM {$this->table_name} ORDER BY nama_jabatan ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("JabatanModel getAll error: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal mengambil data jabatan',
                'data' => []
            ];
        }
    }

    /**
     * Hapus jabatan berdasarkan ID
     *
     * @param int $id - ID jabatan
     * @return array - Response success/error
     */
    public function deleteJabatan($id)
    {
        try {
            // Cek apakah jabatan ada
            $checkQuery = "SELECT id FROM {$this->table_name} WHERE id = :id LIMIT 1";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            if (!$checkStmt->fetch()) {
                return [
                    'success' => false,
                    'message' => 'Jabatan tidak ditemukan'
                ];
            }

            // Hapus jabatan
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

            // Cek error foreign key constraint
            if (strpos($e->getMessage(), 'foreign key') !== false) {
                return [
                    'success' => false,
                    'message' => 'Jabatan tidak bisa dihapus karena masih digunakan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghapus jabatan'
            ];
        }
    }
}
