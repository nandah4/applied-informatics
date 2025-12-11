<?php

/**
 * File: Controllers/ContactController.php
 * Deskripsi: Controller untuk menangani request terkait contact us / pesan masuk
 */

class ContactController
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
    }

    /**
     * Get all pesan dengan pagination dan search
     * Method: GET
     *
     * Query params:
     * - page: int (default: 1)
     * - per_page: int (default: 10)
     * - search: string (cari di nama, email, isi pesan)
     * - status: string (filter: 'Baru' atau 'Dibalas')
     *
     * @return array
     */
    public function getAllPesan()
    {
        // Ambil parameter dari GET request
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';

        // Validasi input
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        // Hitung offset
        $offset = ($page - 1) * $perPage;

        // Siapkan params
        $params = [
            'search' => $search,
            'status' => $status,
            'limit' => $perPage,
            'offset' => $offset
        ];

        // Ambil data dari model
        $result = $this->contactModel->getAllWithSearchAndFilter($params);

        // Generate pagination
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination,
            'total' => $result['total']
        ];
    }

    /**
     * Get pesan by ID
     * Method: GET
     *
     * @param int $id
     * @return array
     */
    public function getPesanById($id)
    {
        return $this->contactModel->getById($id);
    }

    /**
     * Submit contact form dari client (public)
     * Method: POST
     * Endpoint: /contact-us/submit
     *
     * @return void - Return JSON
     */
    public function submitContactForm()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Ambil data dari POST
        $nama_pengirim = $_POST['nama_pengirim'] ?? '';
        $email_pengirim = $_POST['email_pengirim'] ?? '';
        $isi_pesan = $_POST['isi_pesan'] ?? '';

        // 4. Validasi input
        if (empty($nama_pengirim) || empty($email_pengirim) || empty($isi_pesan)) {
            ResponseHelper::error('Semua field wajib diisi');
            return;
        }

        // Validasi email
        $emailValidation = ValidationHelper::validateEmail($email_pengirim);
        if (!$emailValidation['valid']) {
            ResponseHelper::error($emailValidation['message']);
            return;
        }

        // Validasi nama (min 3 karakter)
        $namaValidation = ValidationHelper::validateName($nama_pengirim, 3, 150);
        if (!$namaValidation['valid']) {
            ResponseHelper::error($namaValidation['message']);
            return;
        }

        // Validasi pesan (min 10 karakter)
        if (strlen($isi_pesan) < 10) {
            ResponseHelper::error('Pesan minimal 10 karakter');
            return;
        }

        // 5. Sanitize input
        $data = [
            'nama_pengirim' => htmlspecialchars(strip_tags($nama_pengirim)),
            'email_pengirim' => filter_var($email_pengirim, FILTER_SANITIZE_EMAIL),
            'isi_pesan' => htmlspecialchars(strip_tags($isi_pesan))
        ];

        // 6. Simpan ke database
        $result = $this->contactModel->create($data);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 7. Send confirmation email to sender (optional)
        $this->sendConfirmationEmail($data['email_pengirim'], $data['nama_pengirim']);

        // 8. Return success
        ResponseHelper::success('Pesan Anda berhasil dikirim. Kami akan segera menghubungi Anda.');
    }

    /**
     * Balas pesan dan kirim email balasan
     * Method: POST
     * Endpoint: /admin/contact/balas
     *
     * @return void - Return JSON
     */
    public function balasPesan()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Ambil data dari POST
        $pesan_id = $_POST['pesan_id'] ?? null;
        $balasan_email = $_POST['balasan_email'] ?? ''; // HTML dari Quill
        $catatan_admin = $_POST['catatan_admin'] ?? null;

        // 4. Validasi input
        if (empty($pesan_id) || !is_numeric($pesan_id)) {
            ResponseHelper::error('ID Pesan tidak valid');
            return;
        }

        // Validasi balasan email tidak kosong
        $balasan_text = strip_tags($balasan_email);
        if (empty(trim($balasan_text))) {
            ResponseHelper::error('Balasan email tidak boleh kosong');
            return;
        }

        // 5. Get data pesan untuk ambil email pengirim
        $pesanResult = $this->contactModel->getById($pesan_id);
        if (!$pesanResult['success']) {
            ResponseHelper::error('Data pesan tidak ditemukan');
            return;
        }

        $pesan = $pesanResult['data'];

        // 6. Cek apakah sudah dibalas
        if ($pesan['status'] === 'Dibalas') {
            ResponseHelper::error('Pesan ini sudah dibalas sebelumnya');
            return;
        }

        // 7. Get admin ID dari session
        $admin_id = $_SESSION['user_id'] ?? null;
        if (!$admin_id) {
            ResponseHelper::error('Session admin tidak valid');
            return;
        }

        // 8. Update status di database
        $updateResult = $this->contactModel->balasPesan(
            $pesan_id,
            $admin_id,
            $balasan_email,
            $catatan_admin
        );

        if (!$updateResult['success']) {
            ResponseHelper::error($updateResult['message']);
            return;
        }

        // 9. Kirim email balasan
        $emailResult = EmailHelper::sendReplyEmail(
            $pesan['email_pengirim'],
            $pesan['nama_pengirim'],
            $balasan_email,
            $pesan['isi_pesan'] // Original message untuk context
        );

        // 10. Return success (meskipun email gagal, status tetap berhasil diupdate)
        $message = 'Balasan berhasil dikirim';
        if ($emailResult && !$emailResult['success']) {
            $message .= '. Namun email gagal terkirim: ' . $emailResult['message'];
        } else {
            $message .= '. Email balasan telah dikirim ke ' . $pesan['email_pengirim'];
        }

        ResponseHelper::success($message);
    }

    /**
     * Update catatan admin saja
     * Method: POST
     * Endpoint: /admin/contact/update-catatan
     *
     * @return void - Return JSON
     */
    public function updateCatatanAdmin()
    {
        // Validasi request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token');
            return;
        }

        $pesan_id = $_POST['pesan_id'] ?? null;
        $catatan_admin = $_POST['catatan_admin'] ?? '';

        if (empty($pesan_id)) {
            ResponseHelper::error('ID Pesan tidak valid');
            return;
        }

        // Update catatan
        $result = $this->contactModel->updateCatatanAdmin($pesan_id, $catatan_admin);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        ResponseHelper::success('Catatan berhasil diupdate');
    }

    /**
     * Delete pesan
     * Method: POST
     * Endpoint: /admin/contact/delete/{id}
     *
     * @param int $id
     * @return void - Return JSON
     */
    public function deletePesan($id)
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Validasi ID
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID pesan tidak valid');
            return;
        }

        // 4. Delete dari database
        $result = $this->contactModel->delete($id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 5. Return success response
        ResponseHelper::success('Pesan berhasil dihapus');
    }

    /**
     * Send confirmation email to sender (optional feature)
     *
     * @param string $email
     * @param string $nama
     * @return void
     */
    private function sendConfirmationEmail($email, $nama)
    {
        // Optional: Send auto-reply confirmation
        // EmailHelper::sendContactConfirmation($email, $nama);
    }
}