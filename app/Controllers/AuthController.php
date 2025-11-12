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

        // Get POST data - match with form field names
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validate input
        if (empty($email) || empty($password)) {
            ResponseHelper::error('Email dan Password harus diisi');
            return;
        }

        // Sanitize input to prevent XSS
        $email = $this->sanitizeInput($email);

        // Call auth model to login user
        $result = $this->authModel->login($email, $password);

        // Send response based on result
        if ($result['success']) {
            ResponseHelper::success($result['message'], [
                'user' => $result['user'],
                'redirect' => base_url('dashboard')
            ]);
        } else {
            ResponseHelper::error($result['message']);
        }
    }


    /**
     * Sanitize input to prevent XSS attacks
     * @param string $data
     * @return string
     */
    private function sanitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}
