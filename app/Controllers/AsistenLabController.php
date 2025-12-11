<?php

/**
 * File: Controllers/AsistenLabController.php
 * Deskripsi: Controller untuk menangani request terkait data asisten lab
 */

class AsistenLabController
{
    private $asistenLabModel;

    public function __construct()
    {
        $this->asistenLabModel = new AsistenLabModel();
    }

    /**
     * Get all asisten lab dengan pagination dan search
     * Method: GET
     *
     * Query params:
     * - page: int (default: 1)
     * - per_page: int (default: 10)
     * - search: string (cari di nama, nim, email)
     * - status_aktif: string ('all', 'aktif', 'tidak_aktif')
     *
     * @return array
     */
    public function getAllAsistenLab()
    {
        // Ambil parameter dari GET request
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $statusFilter = isset($_GET['status_aktif']) ? $_GET['status_aktif'] : 'all';

        // Validasi input
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        // Hitung offset
        $offset = ($page - 1) * $perPage;

        // Siapkan params
        $params = [
            'search' => $search,
            'status_aktif' => $statusFilter,
            'limit' => $perPage,
            'offset' => $offset
        ];

        // Ambil data dari model
        $result = $this->asistenLabModel->getAllWithSearchAndFilter($params);

        // Generate pagination
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination,
            'total' => $result['total']
        ];
    }

    /**
     * Get asisten lab by ID
     * Method: GET
     *
     * @param int $id
     * @return array
     */
    public function getAsistenLabById($id)
    {
        return $this->asistenLabModel->getById($id);
    }

    /**
     * Update asisten lab
     * Method: POST
     * Endpoint: /admin/asisten-lab/update
     *
     * @return void - Return JSON
     */
    public function updateAsistenLab()
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

        // 2. Ambil data dari POST
        $id = $_POST['id'] ?? null;
        $nim = $_POST['nim'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $email = $_POST['email'] ?? '';
        $no_hp = $_POST['no_hp'] ?? '';
        $semester = $_POST['semester'] ?? '';
        $link_github = $_POST['link_github'] ?? '';
        $tipe_anggota = $_POST['tipe_anggota'] ?? '';
        $periode_aktif = $_POST['periode_aktif'] ?? '';
        $tanggal_selesai = $_POST['tanggal_selesai'] ?? null;
        $status_aktif = isset($_POST['status_aktif']) ? $_POST['status_aktif'] : '';

        // 3. Validasi input
        if (empty($id) || !is_numeric($id)) {
            ResponseHelper::error('ID Asisten Lab tidak valid');
            return;
        }

        if (empty($nim)) {
            ResponseHelper::error('NIM wajib diisi');
            return;
        }

        if (empty($nama)) {
            ResponseHelper::error('Nama wajib diisi');
            return;
        }

        if (empty($email)) {
            ResponseHelper::error('Email wajib diisi');
            return;
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ResponseHelper::error('Format email tidak valid');
            return;
        }

        if (empty($tipe_anggota)) {
            ResponseHelper::error('Tipe Anggota wajib dipilih');
            return;
        }

        // Validasi semester (wajib)
        if (empty($semester)) {
            ResponseHelper::error('Semester wajib dipilih');
            return;
        }

        if (!is_numeric($semester) || $semester < 1 || $semester > 8) {
            ResponseHelper::error('Semester harus antara 1-8');
            return;
        }

        // Validasi link_github (opsional, tapi jika diisi harus valid URL)
        if (!empty($link_github)) {
            if (!filter_var($link_github, FILTER_VALIDATE_URL)) {
                ResponseHelper::error('Format URL Github tidak valid');
                return;
            }
        }

        // Convert status_aktif to integer (1 or 0) for PostgreSQL boolean field
        // PostgreSQL boolean field accepts: true/false, 1/0, 't'/'f', 'true'/'false'
        // Using integer is most reliable with PDO binding
        $status_aktif_int = ($status_aktif === '1' || $status_aktif === 'true' || $status_aktif === true) ? 1 : 0;

        // 4. Siapkan data untuk update
        $data = [
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'no_hp' => $no_hp,
            'semester' => (int)$semester,
            'link_github' => $link_github,
            'tipe_anggota' => $tipe_anggota,
            'periode_aktif' => $periode_aktif,
            'tanggal_selesai' => !empty($tanggal_selesai) ? $tanggal_selesai : null,
            'status_aktif' => $status_aktif_int
        ];

        // 5. Update data
        $result = $this->asistenLabModel->update($id, $data);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 6. Return success response
        ResponseHelper::success($result['message']);
    }

    /**
     * Delete asisten lab
     * Method: POST
     * Endpoint: /admin/asisten-lab/delete/{id}
     *
     * @param int $id
     * @return void - Return JSON
     */
    public function deleteAsistenLab($id)
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
            ResponseHelper::error('ID asisten lab tidak valid');
            return;
        }

        // 3. Delete dari database
        $result = $this->asistenLabModel->delete($id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 4. Return success response
        ResponseHelper::success('Data asisten lab berhasil dihapus');
    }

    /**
     * Get statistics asisten lab
     * Method: GET
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->asistenLabModel->getStatistics();
    }
}
