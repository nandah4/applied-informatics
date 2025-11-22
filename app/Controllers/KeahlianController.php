<?php

/**
 * File: Controllers/KeahlianController.php
 * Deskripsi: Controller untuk mengelola data keahlian dosen
 *
 * Fungsi:
 * - createKeahlian(): Tambah keahlian baru
 * - getAllKeahlian(): Ambil semua data keahlian
 * - getKeahlianByDosenID(): Ambil keahlian berdasarkan ID dosen
 * - deleteKeahlian(): Hapus keahlian berdasarkan ID
 */

class KeahlianController
{
    private $keahlianModel;

    public function __construct()
    {
        $this->keahlianModel = new KeahlianModel();
    }

    /**
     * Ambil semua data keahlian
     *
     * @return array - Data keahlian
     */
    public function getAllKeahlian()
    {
        return $this->keahlianModel->getAllKeahlian();
    }

    /**
     * Ambil keahlian berdasarkan ID dosen
     *
     * @param int $id - ID dosen
     * @return array - Data keahlian dosen
     */
    public function getKeahlianByDosenID($id)
    {
        return $this->keahlianModel->getKeahlianByDosenID($id);
    }

    /**
     * Tambah keahlian baru
     * Method: POST
     *
     * @return void - JSON response
     */
    public function createKeahlian()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Method tidak valid');
            return;
        }

        $keahlian = $_POST['keahlian'] ?? '';

        // Validasi input
        $validation = ValidationHelper::validateName($keahlian, 1, 255);
        if (!$validation['valid']) {
            ResponseHelper::error($validation['message']);
            return;
        }

        // Simpan ke database
        $result = $this->keahlianModel->createKeahlian($keahlian);

        if ($result['success']) {
            ResponseHelper::success($result['message'], $result['data'] ?? []);
        } else {
            ResponseHelper::error($result['message']);
        }
    }

    /**
     * Hapus keahlian berdasarkan ID
     * Method: POST
     *
     * @return void - JSON response
     */
    public function deleteKeahlian()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Method tidak valid');
            return;
        }

        $id = $_POST['id'] ?? '';

        // Validasi ID
        $validation = ValidationHelper::validateId($id, 'ID keahlian');
        if (!$validation['valid']) {
            ResponseHelper::error($validation['message']);
            return;
        }

        // Hapus dari database
        $result = $this->keahlianModel->deleteKeahlian((int)$id);

        if ($result['success']) {
            ResponseHelper::success($result['message']);
        } else {
            ResponseHelper::error($result['message']);
        }
    }
}
