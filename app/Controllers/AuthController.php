<?php

/**
 * File: controllers/admin/AuthController
 * Description: Handle authentication logic (register, login, + logout)
 */

class AuthController
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }


    /**
     * Handle user login
     * Validate input, and sanitizes data
     */
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // Validate CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // Get POST data - match with form field names
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validate input
        if (empty($email) || empty($password)) {
            ResponseHelper::error('Email dan Password harus diisi');
            return;
        }

        // Sanitize email
        $email = $this->sanitizeEmail($email);

        // Call auth model to login user
        $result = $this->authModel->login($email, $password);

        // Send response based on result
        if ($result['success']) {
            // Regenerate CSRF token setelah login sukses
            CsrfHelper::regenerateToken();

            ResponseHelper::success($result['message'], [
                'redirect' => base_url('admin/dashboard')
            ]);
        } else {
            ResponseHelper::error($result['message']);
        }
    }


    /**
     * Sanitize email input
     * @param string $email
     * @return string
     */
    private function sanitizeEmail($email)
    {
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $email;
    }
}
