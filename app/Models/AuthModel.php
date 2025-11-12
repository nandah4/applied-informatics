<?php

/**
 * File: Models/AuthModel.php
 * Description: Handle database operations for AuthModel
 */



class AuthModel
{
    private $db;
    private $table_name = 'tbl_users';

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Login ke dashbord
     * @param string email
     * @param string password
     * @return array
     */

    public function login($email, $password)
    {
        try {
            $query = "SELECT * FROM $this->table_name WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if user exists
            if (!$user || !password_verify($password, $user['password'])) {
                return [
                    'success' => false,
                    'message' => 'Email atau password salah'
                ];
            }

            // Store user data in session (exclude password)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'] ?? 'guest';
            $_SESSION['logged_in'] = true;

            return [
                'success' => true,
                'message' => 'Login sucess',
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role'] ?? 'user'
                ]
            ];
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ];
        }
    }
}
