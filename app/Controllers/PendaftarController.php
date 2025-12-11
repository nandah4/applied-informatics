<?php

/**
 * File: Controllers/PendaftarController.php
 * Deskripsi: Controller untuk menangani request terkait data pendaftar
 */

class PendaftarController
{
    private $pendaftarModel;

    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
    }

    /**
     * Get all pendaftar dengan pagination dan search
     * Method: GET
     *
     * Query params:
     * - page: int (default: 1)
     * - per_page: int (default: 10)
     * - search: string (cari di nama, nim, email)
     *
     * @return array
     */
    public function getAllPendaftar()
    {
        // Ambil parameter dari GET request
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Validasi input
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        // Hitung offset
        $offset = ($page - 1) * $perPage;

        // Siapkan params
        $params = [
            'search' => $search,
            'status_seleksi' => isset($_GET['status_seleksi']) ? $_GET['status_seleksi'] : 'all',
            'limit' => $perPage,
            'offset' => $offset
        ];

        // Ambil data dari model
        $result = $this->pendaftarModel->getAllWithSearchAndFilter($params);

        // Generate pagination
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination,
            'total' => $result['total']
        ];
    }

    /**
     * Get pendaftar by ID
     * Method: GET
     *
     * @param int $id
     * @return array
     */
    public function getPendaftarById($id)
    {
        return $this->pendaftarModel->getById($id);
    }

    /**
     * Update status seleksi pendaftar
     * Method: POST
     * Endpoint: /admin/daftar-pendaftar/update-status
     *
     * Flow:
     * 1. Validasi input
     * 2. Update status di database (via stored procedure)
     * 3. Kirim email notifikasi (Diterima/Ditolak)
     * 4. Return JSON response
     *
     * @return void - Return JSON
     */
    public function updateStatusSeleksi()
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
        $pendaftar_id = $_POST['pendaftar_id'] ?? null;
        $status_baru = $_POST['status_seleksi'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? null;  // Feedback untuk penolakan

        // 3. Validasi input
        if (empty($pendaftar_id) || !is_numeric($pendaftar_id)) {
            ResponseHelper::error('ID Pendaftar tidak valid');
            return;
        }

        if (empty($status_baru)) {
            ResponseHelper::error('Status seleksi wajib dipilih');
            return;
        }

        // 4. Validasi status yang diperbolehkan
        $allowedStatus = ['Diterima', 'Ditolak'];
        if (!in_array($status_baru, $allowedStatus)) {
            ResponseHelper::error('Status tidak valid. Hanya "Diterima" atau "Ditolak"');
            return;
        }

        // 4A. Validasi deskripsi wajib untuk status Ditolak
        if ($status_baru === 'Ditolak' && empty(trim(strip_tags($deskripsi)))) {
            ResponseHelper::error('Alasan penolakan wajib diisi');
            return;
        }

        // 5. Get data pendaftar untuk email
        $pendaftarResult = $this->pendaftarModel->getById($pendaftar_id);
        if (!$pendaftarResult['success']) {
            ResponseHelper::error('Data pendaftar tidak ditemukan');
            return;
        }

        $pendaftar = $pendaftarResult['data'];

        // 6. Cek apakah status masih Pending (tidak bisa diubah jika sudah diproses)
        if ($pendaftar['status_seleksi'] !== 'Pending') {
            ResponseHelper::error('Status sudah diproses sebelumnya. Tidak dapat diubah kembali.');
            return;
        }

        // 7. Update status di database (dengan deskripsi untuk penolakan)
        $updateResult = $this->pendaftarModel->updateStatusSeleksi(
            $pendaftar_id,
            $status_baru,
            $status_baru === 'Ditolak' ? $deskripsi : null
        );

        if (!$updateResult['success']) {
            ResponseHelper::error($updateResult['message']);
            return;
        }

        // 8. Kirim email notifikasi
        $emailResult = null;
        if ($status_baru === 'Diterima') {
            $emailResult = EmailHelper::sendAcceptanceEmail(
                $pendaftar['email'],
                $pendaftar['nama'],
                $pendaftar['judul_rekrutmen']
            );
        } else {
            $emailResult = EmailHelper::sendRejectionEmail(
                $pendaftar['email'],
                $pendaftar['nama'],
                $pendaftar['judul_rekrutmen'],
                $deskripsi  // Include feedback in rejection email
            );
        }

        // 9. Return success (meskipun email gagal, status tetap berhasil diupdate)
        $message = $updateResult['message'];
        if ($emailResult && !$emailResult['success']) {
            $message .= '. Namun email gagal dikirim: ' . $emailResult['message'];
        } else {
            $message .= '. Email notifikasi telah dikirim ke ' . $pendaftar['email'];
        }

        ResponseHelper::success($message, ['status' => $status_baru]);
    }

    /**
     * Delete pendaftar
     * Method: POST
     * Endpoint: /admin/daftar-pendaftar/delete/{id}
     *
     * @param int $id
     * @return void - Return JSON
     */
    public function deletePendaftar($id)
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
            ResponseHelper::error('ID pendaftar tidak valid');
            return;
        }

        // 3. Delete dari database (file CV & KHS otomatis terhapus di model)
        $result = $this->pendaftarModel->delete($id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 4. Return success response
        ResponseHelper::success('Data pendaftar berhasil dihapus');
    }
}
