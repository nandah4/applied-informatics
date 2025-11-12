<?php

/**
 * ============================================================================
 * FASILITAS CONTROLLER - KERANGKA KERJA
 * ============================================================================
 *
 * Controller ini menangani request untuk fitur fasilitas
 * Isi method dengan implementasi Anda sendiri
 */

class FasilitasController
{
    private $fasilitasModel;

    /**
     * CONSTRUCTOR
     * Inisialisasi FasilitasModel
     */
    public function __construct()
    {
        $this->fasilitasModel = new FasilitasModel();
    }

    /**
     * GET ALL FASILITAS (Untuk View List)
     *
     * Fungsi: Mengambil data dengan pagination
     * Method: GET
     * Return: Array dengan data + pagination info
     */
    public function getAllFasilitas()
    {
        // TODO: Implementasi
        // 1. Ambil parameter page & per_page dari $_GET
        // 2. Validasi parameter
        // 3. Panggil model->getAllFasilitasPaginated()
        // 4. Gunakan PaginationHelper
        // 5. Return data
    }

    /**
     * GET FASILITAS BY ID
     *
     * Fungsi: Mengambil detail 1 fasilitas
     * @param int $id - ID fasilitas
     * Return: Array detail fasilitas
     */
    public function getFasilitasById($id)
    {
        // TODO: Implementasi
        // 1. Validasi ID
        // 2. Panggil model
        // 3. Return data
    }

    /**
     * CREATE FASILITAS (Handle Form Submit)
     *
     * Fungsi: Menambah fasilitas baru
     * Method: POST
     * Response: JSON
     */
    public function createFasilitas()
    {
        // TODO: Implementasi
        // 1. Cek request method POST
        // 2. Ambil data dari $_POST
        // 3. Validasi input
        // 4. Handle upload foto
        // 5. Panggil model->insertFasilitas()
        // 6. Return ResponseHelper::success() atau error()
    }

    /**
     * UPDATE FASILITAS (Handle Edit Form)
     *
     * Fungsi: Update data fasilitas
     * Method: POST
     * Response: JSON
     */
    public function updateFasilitas()
    {
        // TODO: Implementasi
        // 1. Cek request method
        // 2. Ambil data dari $_POST
        // 3. Validasi input
        // 4. Handle upload foto (jika ada)
        // 5. Panggil model->updateFasilitas()
        // 6. Return response
    }

    /**
     * DELETE FASILITAS
     *
     * Fungsi: Hapus fasilitas
     * Method: POST
     * @param int $id - ID fasilitas
     * Response: JSON
     */
    public function deleteFasilitasById($id)
    {
        // TODO: Implementasi
        // 1. Validasi request method
        // 2. Panggil model->deleteFasilitas()
        // 3. Hapus file foto
        // 4. Return response
    }

    /**
     * VALIDATE INPUT (Private Helper)
     *
     * Fungsi: Validasi data form
     * @param array $data - Data yang akan divalidasi
     * Return: Array error messages
     */
    private function validateFasilitasInput($data)
    {
        // TODO: Implementasi validasi
        // 1. Validasi nama_fasilitas
        // 2. Validasi kategori
        // 3. Validasi jumlah
        // 4. Validasi kondisi
        // 5. Return array errors
    }
}
