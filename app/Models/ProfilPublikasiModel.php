<?php

/**
 * File: Models/ProfilPublikasiModel.php
 * Deskripsi: Model untuk menangani operasi database terkait profil publikasi dosen
 *
 * Tabel yang digunakan:
 * - ref_profil_publikasi: Data profil publikasi dosen (SINTA, SCOPUS, dll)
 *
 * Fungsi utama:
 * - insert(): Insert profil publikasi baru
 * - update(): Update URL profil publikasi
 * - delete(): Hapus profil publikasi
 * - getByDosenId(): Ambil semua profil publikasi milik dosen
 */

class ProfilPublikasiModel extends BaseModel
{
    protected $table_name = 'ref_profil_publikasi';

    /**
     * Insert profil publikasi baru
     *
     * @param array $data - Format: [
     *                          'dosen_id' => int,
     *                          'tipe' => string (SINTA|SCOPUS|GOOGLE_SCHOLAR|ORCID|RESEARCHGATE),
     *                          'url_profil' => string
     *                      ]
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function insert($data)
    {
        try {
            $query = "CALL sp_insert_profil_publikasi(
                :dosen_id,
                :tipe,
                :url_profil
            )";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':dosen_id', $data['dosen_id'], PDO::PARAM_INT);
            $stmt->bindParam(':tipe', $data['tipe'], PDO::PARAM_STR);
            $stmt->bindParam(':url_profil', $data['url_profil'], PDO::PARAM_STR);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Profil publikasi berhasil ditambahkan'
            ];

        } catch (PDOException $e) {
            // Handle RAISE EXCEPTION dari stored procedure
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'Dosen tidak ditemukan') !== false) {
                return ['success' => false, 'message' => 'Dosen tidak ditemukan'];
            }

            if (strpos($errorMessage, 'sudah ada untuk dosen ini') !== false) {
                return ['success' => false, 'message' => 'Profil tipe ini sudah ada untuk dosen ini'];
            }

            return [
                'success' => false,
                'message' => 'Gagal menambahkan profil publikasi: ' . $errorMessage
            ];
        }
    }

    /**
     * Update URL profil publikasi
     *
     * @param int $id - ID profil publikasi
     * @param string $url_profil - URL baru
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function update($id, $url_profil)
    {
        try {
            $query = "CALL sp_update_profil_publikasi(:id, :url_profil)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':url_profil', $url_profil, PDO::PARAM_STR);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Profil publikasi berhasil diupdate'
            ];

        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return ['success' => false, 'message' => 'Profil publikasi tidak ditemukan'];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate profil publikasi: ' . $errorMessage
            ];
        }
    }

    /**
     * Delete profil publikasi
     *
     * @param int $id - ID profil publikasi
     * @return array - Format: ['success' => bool, 'message' => string]
     */
    public function delete($id)
    {
        try {
            $query = "CALL sp_delete_profil_publikasi(:id)";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Profil publikasi berhasil dihapus'
            ];

        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'tidak ditemukan') !== false) {
                return ['success' => false, 'message' => 'Profil publikasi tidak ditemukan'];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghapus profil publikasi: ' . $errorMessage
            ];
        }
    }

    /**
     * Get semua profil publikasi milik dosen
     *
     * @param int $dosen_id - ID dosen
     * @return array - Format: ['success' => bool, 'data' => array]
     */
    
    public function getByDosenId($dosen_id)
    {
        try {
            $query = "SELECT id, dosen_id, tipe, url_profil FROM $this->table_name WHERE dosen_id = :dosen_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':dosen_id', $dosen_id, PDO::PARAM_INT);

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'data' => $data
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data profil publikasi: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    
}
