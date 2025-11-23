<?php

/**
 * ============================================================================
 * BASE MODEL - ABSTRACT CLASS
 * ============================================================================
 *
 * File: Core/BaseModel.php
 * Deskripsi: Base class untuk semua Model yang menyediakan:
 *            - Database connection handling
 *            - Common CUD operations template
 *            - Consistent error handling
 *
 * Cara Penggunaan:
 * 1. Extend class ini di Model 
 * 2. Set protected $table_name di child class
 * 3. Implement abstract methods sesuai kebutuhan
 *
 * Contoh:
 * class MitraModel extends BaseModel {
 *     protected $table_name = 'mst_mitra';
 *     public function getAll() { ... }
 * }
 */

abstract class BaseModel
{
    protected $db;
    protected $table_name;

    /**
     * Constructor: Inisialisasi koneksi database
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * ========================================================================
     * ABSTRACT METHODS - Wajib diimplementasikan di child class
     * ========================================================================
     */

    /**
     * Insert new record
     * @param array $data - Data to insert
     * @return array Response format: ['success' => bool, 'data' => ['id' => int], 'message' => string]
     */
    abstract public function insert($data);

    /**
     * Update existing record
     * @param int $id - ID record
     * @param array $data - Data to update
     * @return array Response format: ['success' => bool, 'message' => string]
     */
    abstract public function update($id, $data);

    /**
     * Delete record
     * @param int $id - ID record
     * @return array Response format: ['success' => bool, 'message' => string]
     */
    abstract public function delete($id);

    /**
     * Get database connection
     * @return PDO
     */
    protected function getConnection()
    {
        return $this->db;
    }
}