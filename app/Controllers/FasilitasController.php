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
     * Fungsi: Mengambil data dengan search dan pagination
     * Method: GET
     * @return array - ['data' => array, 'pagination' => array]
     */
    public function getAllFasilitas()
    {
        // Ambil parameter dari GET request
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        // Validasi input
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        // Hitung offset
        $offset = ($page - 1) * $perPage;

        // Siapkan params untuk model
        $params = [
            'search' => $search,
            'limit' => $perPage,
            'offset' => $offset
        ];

        // Get data dari model
        $result = $this->fasilitasModel->getAllWithSearchAndFilter($params);

        // Generate pagination
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination,
            'total' => $result['total']
        ];
    }

    /**
     * GET FASILITAS BY ID
     *
     * Fungsi: Mengambil detail 1 fasilitas
     * @param int $id - ID fasilitas
     * @return array - Data Fasilitas
     */
    public function getFasilitasById($id)
    {
        $validation = ValidationHelper::validateId($id, 'ID Fasilitas');
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
                'data' => null
            ];
        }

        $result = $this->fasilitasModel->getFasilitasById((int)$id);

        return $result;
    }

    /**
     * CREATE FASILITAS (Handle Form Submit)
     *
     * Fungsi: Menambah fasilitas baru
     * Method: POST
     * Endpoint: /admin/fasilitas/create
     * Response: JSON
     */
    public function createFasilitas()
    {
        // Cek request method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // ✅ VALIDASI CSRF TOKEN
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.');
            return;
        }

        // Ambil data dari $_POST
        $nama = $_POST['nama'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        // Validasi input
        $validationErrors = $this->validateFasilitasInput($nama, $deskripsi);
        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        // Handle upload foto
        $fotoFileName = null;
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
        } else {
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

        // ✅ REGENERATE CSRF TOKEN setelah operasi berhasil
        CsrfHelper::regenerateToken();

        // Return success response
        ResponseHelper::success('Data fasilitas berhasil ditambahkan', $result['data']);
    }

    /**
     * UPDATE FASILITAS (Handle Edit Form)
     * 
     * Endpoint: /admin/fasilitas/update
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

        // ✅ VALIDASI CSRF TOKEN
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.');
            return;
        }

        // Ambil data dari $_POST
        $id = $_POST['id'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        // Validasi ID
        $idValidation = ValidationHelper::validateId($id, 'ID Fasilitas');
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
        $oldDataResult = $this->fasilitasModel->getFasilitasById((int)$id);
        if (!$oldDataResult['success']) {
            ResponseHelper::error('Data fasilitas lama tidak ditemukan.');
            return;
        }

        $oldFotoFileName = $oldDataResult['data']['foto'] ?? null;
        $fotoFileName = $oldFotoFileName;

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
        $result = $this->fasilitasModel->updateFasilitas((int)$id, $fasilitas_data);

        // Jika gagal update dan ada foto baru, hapus foto baru tsb
        if (!$result['success']) {
            if ($fotoFileName && $fotoFileName !== $oldFotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'fasilitas');
            }
            ResponseHelper::error($result['message']);
            return;
        }

        // ✅ REGENERATE CSRF TOKEN setelah operasi berhasil
        CsrfHelper::regenerateToken();

        // Return success response
        ResponseHelper::success('Data fasilitas berhasil diupdate');
    }

    /**
     * DELETE FASILITAS
     *
     * Fungsi: Hapus fasilitas
     * Method: POST
     * Endpoint: /admin/fasilitas/delete/{id}
     * Response: JSON
     */
    public function deleteFasilitasById($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.');
            return;
        }

        // Panggil model->deleteFasilitas()
        $result = $this->fasilitasModel->deleteFasilitas((int)$id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // Hapus file foto jika ada
        if (!empty($result['data']['foto'])) {
            FileUploadHelper::delete($result['data']['foto'], 'fasilitas');
        }

        // ✅ REGENERATE CSRF TOKEN setelah operasi berhasil
        CsrfHelper::regenerateToken();

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

        $namaValidation = ValidationHelper::validateName($nama, 3, 150);
        if (!$namaValidation['valid']) {
            $errors[] = $namaValidation['message'];
        }

        $deskripsiValidation = ValidationHelper::validateText($deskripsi, 255, false);
        if (!$deskripsiValidation['valid']) {
            $errors[] = $deskripsiValidation['message'];
        }

        return $errors;
    }
}
