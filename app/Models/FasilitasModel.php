<?php

/**
 * ============================================================================
 * FASILITAS MODEL - KERANGKA KERJA
 * ============================================================================
 *
 * File ini adalah template/skeleton untuk FasilitasModel
 * Isi method dengan implementasi Anda sendiri
 */

class FasilitasModel
{
    private $db;
    private $table_name = 'tbl_fasilitas';

    /**
     * CONSTRUCTOR
     * Inisialisasi koneksi database
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * GET ALL FASILITAS
     *
     * Fungsi: Mengambil semua data fasilitas
     * Return: ['success' => bool, 'message' => string, 'data' => array]
     */
    public function getAllFasilitas()
    {
        // TODO: Implementasi query SELECT semua data
    }

    /**
     * GET ALL FASILITAS (PAGINATION)
     *
     * Fungsi: Mengambil data dengan batasan per halaman
     * @param int $limit - Jumlah data per halaman
     * @param int $offset - Mulai dari data ke berapa
     * Return: ['success' => bool, 'data' => array, 'total' => int]
     */
    public function getAllFasilitasPaginated($limit = 10, $offset = 0)
    {
        // TODO: Implementasi query dengan LIMIT dan OFFSET
    }

    /**
     * GET FASILITAS BY ID
     *
     * Fungsi: Mengambil detail 1 fasilitas
     * @param int $id - ID fasilitas
     * Return: ['success' => bool, 'data' => array]
     */
    public function getFasilitasById($id)
    {
        // TODO: Implementasi query WHERE id = ?
    }

    /**
     * INSERT FASILITAS
     *
     * Fungsi: Tambah fasilitas baru
     * @param array $data - Data fasilitas (nama, kategori, jumlah, dll)
     * Return: ['success' => bool, 'data' => ['id' => int]]
     */
    public function insertFasilitas($data)
    {
        // TODO: Implementasi INSERT INTO
    }

    /**
     * UPDATE FASILITAS
     *
     * Fungsi: Update data fasilitas
     * @param int $id - ID fasilitas
     * @param array $data - Data baru
     * Return: ['success' => bool, 'message' => string]
     */
    public function updateFasilitas($id, $data)
    {
        // TODO: Implementasi UPDATE SET ... WHERE id = ?
    }

    /**
     * DELETE FASILITAS
     *
     * Fungsi: Hapus fasilitas
     * @param int $id - ID fasilitas
     * Return: ['success' => bool, 'data' => ['foto' => string]]
     */
    public function deleteFasilitas($id)
    {
        // TODO: Implementasi DELETE FROM ... WHERE id = ?
    }
}
