<?php

/**
 * ============================================================================
 * FASILITAS CONTROLLER 
 * ============================================================================
 *
 * File: Controllers/FasilitasController.php
 * Deskripsi: Controller untuk menangani request terkait data fasilitas
 * 
 * Fungsi utama:
 * - createFasilitas(): Handle request create fasilitas baru
 * - getAllFasilitas(): Get semua data fasilitas dengan pagination
 * - getFasilitasById(): Get detail fasilitas berdasarkan ID
 * - updateFasilitas(): Handle request update fasilitas
 * - deleteFasilitasById(): Handle request delete fasilitas
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
     * @return array - ['data' => array, 'pagination' => array]
     */
    public function getAllFasilitas()
    {
        // Ambil parameter page & per_page dari $_GET
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        // Validasi parameter
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage)); // Max 100 per page

        // Generate pagination data
        // Ambil total records DARI MODEL dengan $countOnly = true
        $paginationData = $this->fasilitasModel->getAllFasilitasPaginated($perPage, 0, true);
        $totalRecords = $paginationData['total'];

        // Gunakan PaginationHelper
        $pagination = PaginationHelper::paginate($totalRecords, $page, $perPage);

        // Panggil model->getAllFasilitasPaginated()
        $result = $this->fasilitasModel->getAllFasilitasPaginated($pagination['per_page'], $pagination['offset']);

        return [
            'data' => $result['data'],
            'pagination' => $pagination
        ];
    }

    /**
     * GET FASILITAS BY ID
     *
     * Fungsi: Mengambil detail 1 fasilitas
     * @param int $fasilitas_id - ID fasilitas
     * @return array - Data Fasilitas
     */
    public function getFasilitasById($fasilitas_id)
    {
        // Validasi ID
        $validation = ValidationHelper::validateId($fasilitas_id, 'ID Fasilitas');
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
                'data' => null
            ];
        }

        // Panggil model
        $result = $this->fasilitasModel->getFasilitasById((int)$fasilitas_id);

        // Return data
        return $result;
    }

    /**
     * CREATE FASILITAS (Handle Form Submit)
     *
     * Fungsi: Menambah fasilitas baru
     * Method: POST
     * Endpoint: /applied-informatics/fasilitas/create
     * Response: JSON
     */
    public function createFasilitas()
    {
        // Cek request method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // Ambil data dari $_POST (sesuai nama field form)
        $nama = $_POST['nama'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        // Validasi input
        $validationErrors = $this->validateFasilitasInput($nama, $deskripsi);
        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]); // Tampilkan error pertama
            return;
        }

        // Handle upload foto
        $fotoFileName = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['foto'],
                'image',           // Tipe upload: image
                'fasilitas',       // Folder: uploads/fasilitas/
                2 * 1024 * 1024    // Max size: 2MB
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            $fotoFileName = $uploadResult['filename'];
        } else {
            // Foto wajib di mode create (sesuai validasi form.js)
            ResponseHelper::error('Foto fasilitas wajib diisi.');
            return;
        }

        // Siapkan data untuk insert
        $fasilitas_data = [
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'foto' => $fotoFileName
        ];

        // Panggil model->insertFasilitas()
        $result = $this->fasilitasModel->insertFasilitas($fasilitas_data);

        // Jika gagal insert, hapus foto yang sudah terlanjur di-upload
        if (!$result['success']) {
            if ($fotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'fasilitas');
            }
            ResponseHelper::error($result['message']);
            return;
        }

        // Return success response
        ResponseHelper::success('Data fasilitas berhasil ditambahkan', $result['data']);
    }

    /**
     * UPDATE FASILITAS (Handle Edit Form)
     * 
     * Endpoint: /applied-informatics/fasilitas/update
     * Fungsi: Update data fasilitas
     * Method: POST
     * Response: JSON
     */
    public function updateFasilitas()
    {
        // Cek request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // Ambil data dari $_POST (sesuai nama field form)
        $fasilitas_id = $_POST['fasilitas_id'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        // Validasi ID
        $idValidation = ValidationHelper::validateId($fasilitas_id, 'ID Fasilitas');
        if (!$idValidation['valid']) {
            ResponseHelper::error($idValidation['message']);
            return;
        }

        // Validasi input (nama & deskripsi)
        $validationErrors = $this->validateFasilitasInput($nama, $deskripsi);
        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        // Ambil data lama untuk mendapatkan nama file foto lama
        $oldDataResult = $this->fasilitasModel->getFasilitasById((int)$fasilitas_id);
        if (!$oldDataResult['success']) {
            ResponseHelper::error('Data fasilitas lama tidak ditemukan.');
            return;
        }

        $oldFotoFileName = $oldDataResult['data']['foto'] ?? null;
        $fotoFileName = $oldFotoFileName; // Default: pakai foto lama

        // Handle upload foto baru (opsional di mode edit)
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['foto'],
                'image',
                'fasilitas',
                2 * 1024 * 1024
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            $fotoFileName = $uploadResult['filename'];

            // Hapus foto lama SETELAH upload berhasil
            if ($oldFotoFileName && $oldFotoFileName !== $fotoFileName) {
                FileUploadHelper::delete($oldFotoFileName, 'fasilitas');
            }
        }

        // Siapkan data untuk update
        $fasilitas_data = [
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'foto' => $fotoFileName
        ];

        // Panggil model->updateFasilitas()
        $result = $this->fasilitasModel->updateFasilitas((int)$fasilitas_id, $fasilitas_data);

        // Jika gagal update dan ada foto baru, hapus foto baru tsb
        if (!$result['success']) {
            if ($fotoFileName && $fotoFileName !== $oldFotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'fasilitas');
            }
            ResponseHelper::error($result['message']);
            return;
        }

        // Return success response
        ResponseHelper::success('Data fasilitas berhasil diupdate');
    }

    /**
     * DELETE FASILITAS
     *
     * Fungsi: Hapus fasilitas
     * Method: POST
     * Endpoint: /applied-informatics/fasilitas/delete/{id}
     * Response: JSON
     */
    public function deleteFasilitasById($fasilitas_id)
    {
        // Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // Panggil model->deleteFasilitas()
        $result = $this->fasilitasModel->deleteFasilitas((int)$fasilitas_id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // Hapus file foto jika ada
        if (!empty($result['data']['foto'])) {
            FileUploadHelper::delete($result['data']['foto'], 'fasilitas');
        }

        // Return success response
        ResponseHelper::success($result['message']);
    }

    /**
     * VALIDATE INPUT (Private Helper)
     *
     * Fungsi: Validasi data form
     * @param string $nama - Nama fasilitas
     * @param string $deskripsi - Deskripsi fasilitas
     * @return array - Array error messages
     */
    private function validateFasilitasInput($nama, $deskripsi)
    {
        $errors = [];

        // 1. Validasi nama (wajib, min 3 char, max 150 char)
        $namaValidation = ValidationHelper::validateName($nama, 3, 150);
        if (!$namaValidation['valid']) {
            $errors[] = $namaValidation['message'];
        }

        // 2. Validasi deskripsi (opsional, max 5000 char)
        // TEXT di PostgreSQL bisa unlimited, tapi kita batasi 5000 untuk UX
        $deskripsiValidation = ValidationHelper::validateText($deskripsi, 5000, false);
        if (!$deskripsiValidation['valid']) {
            $errors[] = $deskripsiValidation['message'];
        }

        return $errors;
    }
}
