<?php

/**
 * File: config/database.php
 * Description: Database configuration settings.
 */


// Validate required environment variables
$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS']);


class Database
{
    private $host;
    private $db_name;
    private $port;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
        $this->port = $_ENV['DB_PORT'];
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $dsn  = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
