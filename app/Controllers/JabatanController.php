<?php

/**
 * File: Controllers/JabatanController.php
 * Deskripsi: Controller untuk mengelola data jabatan dosen
 *
 * Fungsi:
 * - createJabatan(): Tambah jabatan baru
 * - getAllJabatan(): Ambil semua data jabatan
 * - deleteJabatan(): Hapus jabatan berdasarkan ID
 */

class JabatanController
{
    private $jabatanModel;

    public function __construct()
    {
        $this->jabatanModel = new JabatanModel();
    }

    /**
     * Tambah jabatan baru
     * Method: POST
     *
     * @return void - JSON response
     */
    public function createJabatan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Method tidak valid');
            return;
        }

        $jabatan = $_POST['jabatan'] ?? '';

        // Validasi input
        $validation = ValidationHelper::validateName($jabatan, 2, 200);
        if (!$validation['valid']) {
            ResponseHelper::error($validation['message']);
            return;
        }

        // Simpan ke database
        $result = $this->jabatanModel->createJabatan($jabatan);

        if ($result['success']) {
            ResponseHelper::success($result['message'], $result['data'] ?? []);
        } else {
            ResponseHelper::error($result['message']);
        }
    }

    /**
     * Ambil semua data jabatan
     *
     * @return array - Data jabatan
     */
    public function getAllJabatan()
    {
        return $this->jabatanModel->getAllJabatan();
    }

    /**
     * Hapus jabatan berdasarkan ID
     * Method: POST
     *
     * @return void - JSON response
     */
    public function deleteJabatan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Method tidak valid');
            return;
        }

        $id = $_POST['id'] ?? '';

        // Validasi ID
        $validation = ValidationHelper::validateId($id, 'ID jabatan');
        if (!$validation['valid']) {
            ResponseHelper::error($validation['message']);
            return;
        }

        // Hapus dari database
        $result = $this->jabatanModel->deleteJabatan((int)$id);

        if ($result['success']) {
            ResponseHelper::success($result['message']);
        } else {
            ResponseHelper::error($result['message']);
        }
    }
}
