<?php

/**
 * File: Controllers/PublikasiAkademikController.php
 * Deskripsi: Controller untuk menangani request terkait data publikasi akademik dosen
 *
 * Fungsi utama:
 * - getAllPublikasiWithPagination(): Get semua data publikasi dengan pagination
 * - getPublikasiById(): Get detail publikasi by ID
 * - createPublikasi(): Handle request create publikasi baru
 * - updatePublikasi(): Handle request update publikasi
 * - deletePublikasiAkademik(): Handle request delete publikasi
 */

class PublikasiAkademikController
{
    private $publikasiAkademikModel;

    /**
     * Constructor: Inisialisasi models
     */
    public function __construct()
    {
        $this->publikasiAkademikModel = new PublikasiAkademikModel();
    }


    // ========================================
    // PUBLIKASI AKADEMIK CRUD OPERATIONS
    // ========================================

    /**
     * Get all publikasi akademik dengan pagination
     * Method: GET
     * Endpoint: /applied-informatics/admin/publikasi-akademik
     *
     * @param int $page - Halaman saat ini
     * @param int $perPage - Jumlah data per halaman
     * @return array - Data publikasi dan pagination info
     */
    public function getAllPublikasiWithPagination($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        $result = $this->publikasiAkademikModel->getAllWithPagination($perPage, $offset);

        // Generate pagination dari total yang dikembalikan model
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination
        ];
    }

    /**
     * Handle request untuk create publikasi akademik baru
     * Method: POST
     * Endpoint: /applied-informatics/admin/publikasi-akademik/create
     *
     * @return void - Mengembalikan JSON response
     */

    public function createPublikasi()
    {
        // 1. Validasi request method
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Ambil dan sanitasi input
        $dosen_id = $_POST['dosen_id'] ?? '';
        $judul = trim($_POST['judul'] ?? '');
        $tipe_publikasi = $_POST['tipe_publikasi'] ?? '';
        $tahun_publikasi = $_POST['tahun_publikasi'] ?? '';
        $url_publikasi = trim($_POST['url_publikasi'] ?? '');

        // 4. Validasi dosen_id (harus numeric dan tidak kosong)
        if (empty($dosen_id) || !is_numeric($dosen_id)) {
            ResponseHelper::error('Dosen harus dipilih');
            return;
        }

        // 5. Validasi judul (minimal 5 karakter, maksimal 500)
        if (empty($judul)) {
            ResponseHelper::error('Judul publikasi tidak boleh kosong');
            return;
        }
        if (strlen($judul) < 2) {
            ResponseHelper::error('Judul publikasi minimal 2 karakter');
            return;
        }
        if (strlen($judul) > 500) {
            ResponseHelper::error('Judul publikasi maksimal 500 karakter');
            return;
        }

        // 6. Validasi tipe publikasi (whitelist)
        $allowedTipe = ['Riset', 'Kekayaan Intelektual', 'PPM'];
        if (empty($tipe_publikasi) || !in_array($tipe_publikasi, $allowedTipe)) {
            ResponseHelper::error('Tipe publikasi tidak valid');
            return;
        }

        // 7. Validasi tahun publikasi
        if (empty($tahun_publikasi)) {
            ResponseHelper::error('Tahun publikasi tidak boleh kosong');
            return;
        }
        if (!is_numeric($tahun_publikasi)) {
            ResponseHelper::error('Tahun publikasi harus berupa angka');
            return;
        }
        $tahun = (int) $tahun_publikasi;
        $currentYear = (int) date('Y');
        if ($tahun < 1900 || $tahun > ($currentYear + 1)) {
            ResponseHelper::error('Tahun publikasi tidak valid (1900 - ' . ($currentYear + 1) . ')');
            return;
        }

        // 8. Validasi URL publikasi (opsional)
        // if (!empty($url_publikasi)) {
        //     if (!filter_var($url_publikasi, FILTER_VALIDATE_URL)) {
        //         ResponseHelper::error('URL publikasi tidak valid');
        //         return;
        //     }
        // }

        // 9. Siapkan data untuk model
        $data = [
            'dosen_id' => (int) $dosen_id,
            'judul' => $judul,
            'tipe_publikasi' => $tipe_publikasi,
            'tahun_publikasi' => $tahun,
            'url_publikasi' => $url_publikasi
        ];

        // 10. Simpan ke database
        $result = $this->publikasiAkademikModel->insert($data);

        if ($result['success']) {
            ResponseHelper::success($result['message']);
        } else {
            ResponseHelper::error($result['message']);
        }
    }

    /**
     * Handle request untuk update publikasi akademik
     * Method: POST
     * Endpoint: /applied-informatics/admin/publikasi-akademik/update
     *
     * @return void - Mengembalikan JSON response
     */
    public function updatePublikasi()
    {
        // 1. Validasi request method
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Ambil dan sanitasi input
        $id = $_POST['id'];
        $dosen_id = $_POST['dosen_id'] ?? '';
        $judul = trim($_POST['judul'] ?? '');
        $tipe_publikasi = $_POST['tipe_publikasi'] ?? '';
        $tahun_publikasi = $_POST['tahun_publikasi'] ?? '';
        $url_publikasi = trim($_POST['url_publikasi'] ?? '');

        // 4. Validasi dosen_id (harus numeric dan tidak kosong)
        if (empty($dosen_id) || !is_numeric($dosen_id)) {
            ResponseHelper::error('Dosen harus dipilih');
            return;
        }

        // 5. Validasi judul (minimal 5 karakter, maksimal 500)
        if (empty($judul)) {
            ResponseHelper::error('Judul publikasi tidak boleh kosong');
            return;
        }
        if (strlen($judul) < 2) {
            ResponseHelper::error('Judul publikasi minimal 2 karakter');
            return;
        }
        if (strlen($judul) > 500) {
            ResponseHelper::error('Judul publikasi maksimal 500 karakter');
            return;
        }

        // 6. Validasi tipe publikasi (whitelist)
        $allowedTipe = ['Riset', 'Kekayaan Intelektual', 'PPM'];
        if (empty($tipe_publikasi) || !in_array($tipe_publikasi, $allowedTipe)) {
            ResponseHelper::error('Tipe publikasi tidak valid');
            return;
        }

        // 7. Validasi tahun publikasi
        if (empty($tahun_publikasi)) {
            ResponseHelper::error('Tahun publikasi tidak boleh kosong');
            return;
        }
        if (!is_numeric($tahun_publikasi)) {
            ResponseHelper::error('Tahun publikasi harus berupa angka');
            return;
        }
        $tahun = (int) $tahun_publikasi;
        $currentYear = (int) date('Y');
        if ($tahun < 1900 || $tahun > ($currentYear + 1)) {
            ResponseHelper::error('Tahun publikasi tidak valid (1900 - ' . ($currentYear + 1) . ')');
            return;
        }

        // 9. Siapkan data untuk model
        $data = [
            'dosen_id' => (int) $dosen_id,
            'judul' => $judul,
            'tipe_publikasi' => $tipe_publikasi,
            'tahun_publikasi' => $tahun,
            'url_publikasi' => $url_publikasi
        ];

        // 10. Simpan ke database
        $result = $this->publikasiAkademikModel->update($id, $data);

        if ($result['success']) {
            ResponseHelper::success($result['message']);
        } else {
            ResponseHelper::error($result['message']);
        }
    }

    /**
     * Get detail publikasi by ID
     * Method: GET
     * Endpoint: /applied-informatics/admin/publikasi-akademik/read/{id}
     *
     * @param int $id - ID publikasi
     * @return array - Data publikasi atau error message
     */
    public function getPublikasiById($id)
    {
        return $this->publikasiAkademikModel->getById($id);
    }

    /**
     * Handle request untuk delete publikasi akademik
     * Method: POST
     * Endpoint: /applied-informatics/admin/publikasi-akademik/delete/{id}
     *
     * @param int $id - ID publikasi yang akan dihapus
     * @return void - Mengembalikan JSON response
     */
    public function deletePublikasiAkademik($id)
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 1A. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 2. Validasi ID
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID publikasi tidak valid');
            return;
        }

        // 3. Delete dari database
        $result = $this->publikasiAkademikModel->delete($id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 5. Return success response
        ResponseHelper::success('Data publikasi akademik berhasil dihapus');
    }
}
