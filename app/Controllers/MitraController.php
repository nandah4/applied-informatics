<?php

/**
 * File: Controllers/MitraController.php
 * Deskripsi: Controller untuk menangani request terkait data mitra
 *
 * Fungsi utama:
 * - createMitra(): Handle request create mitra baru
 */

class MitraController
{
    private $mitraModel;

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
    }

    // ========================================
    // MITRA CRUD OPERATIONS
    // ========================================

    /**
     * Handle request untuk create mitra baru
     * Method: POST
     * Endpoint: /applied-informatics/mitra/create
     *
     * @return void - Mengembalikan JSON response
     */
    public function createMitra()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Ambil data dari POST
        $nama = $_POST['nama'] ?? '';
        $status = $_POST['status'] ?? 'aktif';
        $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
        $tanggal_akhir = $_POST['tanggal_akhir'] ?? null;
        $deskripsi = $_POST['deskripsi'] ?? '';

        // 3. Validasi input
        $validationErrors = $this->validateMitraInput($nama, $tanggal_mulai, $tanggal_akhir);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]); // Return error pertama
            return;
        }

        // 4. Handle upload logo mitra
        $logoFileName = null;
        if (isset($_FILES['logo_mitra']) && $_FILES['logo_mitra']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['logo_mitra'],
                'image',
                'mitra',
                2 * 1024 * 1024
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            $logoFileName = $uploadResult['filename'];
        }

        // 5. Siapkan data untuk insert
        $mitraData = [
            'nama' => $nama,
            'status' => $status,
            'logo_mitra' => $logoFileName,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
            'deskripsi' => $deskripsi
        ];

        // 6. Insert ke database
        $result = $this->mitraModel->insert($mitraData);

        if (!$result['success']) {
            // Jika gagal dan ada logo yang sudah diupload, hapus logonya
            if ($logoFileName) {
                FileUploadHelper::delete($logoFileName, 'mitra');
            }

            ResponseHelper::error($result['message']);
            return;
        }

        // 7. Return success response
        $mitra_id = $result['data']['id'] ?? null;
        ResponseHelper::success('Data mitra berhasil ditambahkan', [
            'id' => $mitra_id
        ]);
    }

    /**
     * Validasi input untuk create/update mitra
     *
     * @param string $nama
     * @param string $tanggal_mulai
     * @param string $tanggal_akhir
     * @return array - Array of error messages (kosong jika valid)
     */
    private function validateMitraInput($nama, $tanggal_mulai, $tanggal_akhir)
    {
        $errors = [];

        // Validasi nama
        if (empty($nama)) {
            $errors[] = "Nama mitra wajib diisi";
        } else {
            $namaValidation = ValidationHelper::validateName($nama, 1, 255);
            if (!$namaValidation['valid']) {
                $errors[] = $namaValidation['message'];
            }
        }

        // Validasi tanggal mulai
        if (empty($tanggal_mulai)) {
            $errors[] = "Tanggal mulai kerjasama wajib diisi";
        }

        // Validasi tanggal akhir (opsional, tapi jika diisi harus lebih besar dari tanggal mulai)
        if (!empty($tanggal_akhir) && !empty($tanggal_mulai)) {
            if ($tanggal_akhir < $tanggal_mulai) {
                $errors[] = "Tanggal akhir tidak boleh lebih awal dari tanggal mulai";
            }
        }

        return $errors;
    }
}
