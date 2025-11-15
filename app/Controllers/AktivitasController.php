<?php

/**
 * File: Controllers/AktivitasController.php
 * Deskripsi: Controller untuk menangani request terkait data aktivitas laboratorium
 *
 * Fungsi utama:
 * - createAktivitas(): Handle request create aktivitas baru
 * - updateAktivitas(): Handle request update aktivitas
 * - deleteAktivitas(): Handle request delete aktivitas
 */

class AktivitasController
{
    private $aktivitasModel;

    public function __construct()
    {
        $this->aktivitasModel = new AktivitasModel();
    }

    // ========================================
    // AKTIVITAS CRUD OPERATIONS
    // ========================================

    /**
     * Get all aktivitas for index page
     * Method: GET
     *
     * @return array
     */
    public function getAllAktivitas()
    {
        return $this->aktivitasModel->getAll();
    }

    /**
     * Ambil semua data aktivitas dengan pagination
     * Method: GET
     *
     * @param int $page - Halaman saat ini
     * @param int $perPage - Item per halaman (default 10)
     * @return array - ['success', 'data', 'pagination']
     */
    public function getAllAktivitasWithPagination($page = 1, $perPage = 10)
    {
        // Hitung total data
        $totalRecords = $this->aktivitasModel->getTotalRecords();

        // Generate pagination data
        $pagination = PaginationHelper::paginate($totalRecords, $page, $perPage);

        // Ambil data dengan limit dan offset
        $aktivitas = $this->aktivitasModel->getAllWithLimit($pagination['per_page'], $pagination['offset']);

        return [
            'success' => true,
            'data' => $aktivitas['data'] ?? [],
            'pagination' => $pagination
        ];
    }

    /**
     * Get aktivitas by ID
     * Method: GET
     *
     * @param int $id
     * @return array
     */
    public function getAktivitasById($id)
    {
        return $this->aktivitasModel->getById($id);
    }

    /**
     * Handle request untuk create aktivitas baru
     * Method: POST
     * Endpoint: /applied-informatics/aktivitas/create
     *
     * @return void - Mengembalikan JSON response
     */
    public function createAktivitas()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Ambil data dari POST
        $judul_aktivitas = $_POST['judul'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $tanggal_kegiatan = $_POST['tanggal_kegiatan'] ?? '';

        // 3. Validasi input
        $validationErrors = $this->validateAktivitasInput($judul_aktivitas, $deskripsi, $tanggal_kegiatan);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]); // Return error pertama
            return;
        }

        // 4. Handle upload foto aktivitas (REQUIRED untuk create)
        if (!isset($_FILES['foto_aktivitas']) || $_FILES['foto_aktivitas']['error'] === UPLOAD_ERR_NO_FILE) {
            ResponseHelper::error('Foto aktivitas wajib diupload');
            return;
        }

        $uploadResult = FileUploadHelper::upload(
            $_FILES['foto_aktivitas'],
            'image',
            'aktivitas-lab',
            2 * 1024 * 1024
        );

        if (!$uploadResult['success']) {
            ResponseHelper::error($uploadResult['message']);
            return;
        }

        $fotoFileName = $uploadResult['filename'];

        // 5. Siapkan data untuk insert
        $aktivitasData = [
            'judul_aktivitas' => $judul_aktivitas,
            'deskripsi' => $deskripsi,
            'foto_aktivitas' => $fotoFileName,
            'tanggal_kegiatan' => $tanggal_kegiatan
        ];

        // 6. Insert ke database
        $result = $this->aktivitasModel->insert($aktivitasData);

        if (!$result['success']) {
            // Jika gagal dan ada foto yang sudah diupload, hapus fotonya
            if ($fotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'aktivitas-lab');
            }

            ResponseHelper::error($result['message']);
            return;
        }

        // 7. Return success response
        ResponseHelper::success('Data aktivitas berhasil ditambahkan');
    }

    /**
     * Validasi input untuk create/update aktivitas
     *
     * @param string $judul_aktivitas
     * @param string $deskripsi
     * @param string $tanggal_kegiatan
     * @return array - Array of error messages (kosong jika valid)
     */
    private function validateAktivitasInput($judul_aktivitas, $deskripsi, $tanggal_kegiatan)
    {
        $errors = [];

        // Validasi judul aktivitas
        if (empty($judul_aktivitas)) {
            $errors[] = "Judul aktivitas wajib diisi";
        } else {
            $judulValidation = ValidationHelper::validateName($judul_aktivitas, 5, 255);
            if (!$judulValidation['valid']) {
                $errors[] = $judulValidation['message'];
            }
        }

        // Validasi deskripsi
        if (empty($deskripsi)) {
            $errors[] = "Deskripsi wajib diisi";
        } elseif (strlen($deskripsi) < 10) {
            $errors[] = "Deskripsi minimal 10 karakter";
        }

        // Validasi tanggal kegiatan
        if (empty($tanggal_kegiatan)) {
            $errors[] = "Tanggal kegiatan wajib diisi";
        }

        return $errors;
    }

    /**
     * Handle request untuk update aktivitas
     * Method: POST
     * Endpoint: /applied-informatics/aktivitas/update
     *
     * @return void - Mengembalikan JSON response
     */
    public function updateAktivitas()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Ambil data dari POST
        $id = $_POST['id'] ?? null;
        $judul_aktivitas = $_POST['judul'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $tanggal_kegiatan = $_POST['tanggal_kegiatan'] ?? '';

        // 3. Validasi ID
        if (!$id) {
            ResponseHelper::error('ID aktivitas tidak valid');
            return;
        }

        // 4. Validasi input
        $validationErrors = $this->validateAktivitasInput($judul_aktivitas, $deskripsi, $tanggal_kegiatan);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        // 5. Get data aktivitas lama untuk foto
        $oldAktivitasResult = $this->aktivitasModel->getById($id);
        if (!$oldAktivitasResult['success']) {
            ResponseHelper::error('Aktivitas tidak ditemukan');
            return;
        }

        $oldAktivitas = $oldAktivitasResult['data'];
        $fotoFileName = $oldAktivitas['foto_aktivitas'];

        // 6. Handle upload foto baru (jika ada)
        if (isset($_FILES['foto_aktivitas']) && $_FILES['foto_aktivitas']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['foto_aktivitas'],
                'image',
                'aktivitas-lab',
                2 * 1024 * 1024
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            // Hapus foto lama jika ada
            if ($oldAktivitas['foto_aktivitas']) {
                FileUploadHelper::delete($oldAktivitas['foto_aktivitas'], 'aktivitas-lab');
            }

            $fotoFileName = $uploadResult['filename'];
        }

        // 7. Siapkan data untuk update
        $aktivitasData = [
            'judul_aktivitas' => $judul_aktivitas,
            'deskripsi' => $deskripsi,
            'foto_aktivitas' => $fotoFileName,
            'tanggal_kegiatan' => $tanggal_kegiatan
        ];

        // 8. Update ke database
        $result = $this->aktivitasModel->update($id, $aktivitasData);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 9. Return success response
        ResponseHelper::success('Data aktivitas berhasil diupdate', ['id' => $id]);
    }

    /**
     * Handle request untuk delete aktivitas
     * Method: POST
     * Endpoint: /applied-informatics/aktivitas/delete/{id}
     *
     * @param int $id
     * @return void - Mengembalikan JSON response
     */
    public function deleteAktivitas($id)
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi ID
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID aktivitas tidak valid');
            return;
        }

        // 3. Delete dari database
        $result = $this->aktivitasModel->delete($id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 4. Hapus file foto jika ada
        if (isset($result['data']['foto_aktivitas']) && $result['data']['foto_aktivitas']) {
            FileUploadHelper::delete($result['data']['foto_aktivitas'], 'aktivitas-lab');
        }

        // 5. Return success response
        ResponseHelper::success('Data aktivitas berhasil dihapus');
    }
}
