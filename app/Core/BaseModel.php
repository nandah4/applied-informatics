<?php

/**
 * ============================================================================
 * BASE MODEL - ABSTRACT CLASS
 * ============================================================================
 *
 * File: Core/BaseModel.php
 * Deskripsi: Base class untuk semua Model yang menyediakan:
 *            - Database connection handling
 *            - Common CRUD operations template
 *            - Shared utility methods
 *            - Consistent error handling
 *
 * Cara Penggunaan:
 * 1. Extend class ini di Model 
 * 2. Set protected $table_name di child class
 * 3. Implement abstract methods sesuai kebutuhan
 *
 * Contoh:
 * class MitraModel extends BaseModel {
 *     protected $table_name = 'tbl_mitra';
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
     * Get all records
     * @return array Response format: ['success' => bool, 'data' => array, 'message' => string]
     */
    // abstract public function getAll();

    /**
     * Get record by ID
     * @param int $id - ID record
     * @return array Response format: ['success' => bool, 'data' => array, 'message' => string]
     */
    // abstract public function getById($id);

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
    // abstract public function update($id, $data);

    /**
     * Delete record
     * @param int $id - ID record
     * @return array Response format: ['success' => bool, 'message' => string]
     */
    // abstract public function delete($id);

    /**
     * ========================================================================
     * SHARED HELPER METHODS - Bisa digunakan semua child class
     * ========================================================================
     */

    /**
     * Execute prepared query with parameters
     *
     * @param string $query - SQL query dengan placeholders
     * @param array $params - Parameters untuk bind (optional)
     * @return PDOStatement
     *
     * Contoh:
     * $stmt = $this->executeQuery(
     *     "SELECT * FROM users WHERE email = :email",
     *     [':email' => $email]
     * );
     */
    protected function executeQuery($query, $params = [])
    {
        try {
            $stmt = $this->db->prepare($query);

            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Find single record by condition
     *
     * @param array $conditions - Conditions ['column' => 'value']
     * @return array|null
     *
     * Contoh:
     * $user = $this->findOne(['email' => 'test@example.com']);
     */
    protected function findOne($conditions = [])
    {
        $whereClauses = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $whereString = empty($whereClauses) ? '1=1' : implode(' AND ', $whereClauses);
        $query = "SELECT * FROM {$this->table_name} WHERE $whereString LIMIT 1";

        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Find multiple records by condition
     *
     * @param array $conditions - Conditions ['column' => 'value']
     * @param string $orderBy - Order by clause (optional)
     * @param int $limit - Limit records (optional)
     * @return array
     *
     * Contoh:
     * $users = $this->find(['status' => 'active'], 'created_at DESC', 10);
     */
    protected function find($conditions = [], $orderBy = '', $limit = null)
    {
        $whereClauses = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $whereString = empty($whereClauses) ? '1=1' : implode(' AND ', $whereClauses);
        $query = "SELECT * FROM {$this->table_name} WHERE $whereString";

        if (!empty($orderBy)) {
            $query .= " ORDER BY $orderBy";
        }

        if ($limit !== null) {
            $query .= " LIMIT $limit";
        }

        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count records by condition
     *
     * @param array $conditions - Conditions ['column' => 'value']
     * @return int
     */
    protected function count($conditions = [])
    {
        $whereClauses = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $whereString = empty($whereClauses) ? '1=1' : implode(' AND ', $whereClauses);
        $query = "SELECT COUNT(*) as total FROM {$this->table_name} WHERE $whereString";

        $stmt = $this->executeQuery($query, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $result['total'];
    }

    /**
     * Check if record exists
     *
     * @param array $conditions - Conditions ['column' => 'value']
     * @return bool
     */
    protected function exists($conditions = [])
    {
        return $this->count($conditions) > 0;
    }

    /**
     * Begin transaction
     */
    protected function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    /**
     * Commit transaction
     */
    protected function commit()
    {
        $this->db->commit();
    }

    /**
     * Rollback transaction
     */
    protected function rollback()
    {
        $this->db->rollBack();
    }

    /**
     * Get table name
     * @return string
     */
    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * Get database connection
     * @return PDO
     */
    protected function getConnection()
    {
        return $this->db;
    }
}