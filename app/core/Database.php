<?php
// app/core/Database.php

// Load the database configuration file to make constants available
require_once __DIR__ . '/../../config/database.php';

class Database {
    private static $instance = null;
    private $pdo;
    private $stmt;

    private function __construct() {
        // Ensure all required database constants are defined
        if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_CHARSET') || !defined('DB_USER') || !defined('DB_PASS')) {
            die("Error: One or more required DB constants (DB_HOST, DB_NAME, etc.) are not defined in the config file.");
        }

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In a production environment, you might want to log this error instead of dying
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get the singleton instance of the Database class.
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Prepare a statement with an SQL query.
     * @param string $sql The SQL query.
     */
    public function query($sql) {
        $this->stmt = $this->pdo->prepare($sql);
    }

    /**
     * Bind values to the prepared statement.
     * @param mixed $param The parameter identifier.
     * @param mixed $value The value to bind.
     * @param int|null $type The explicit data type for the parameter.
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Execute the prepared statement.
     * @return bool Returns true on success or false on failure.
     */
    public function execute() {
        return $this->stmt->execute();
    }

    /**
     * Get the result set as an array of associative arrays.
     * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Get a single record as an associative array.
     * @return mixed
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Get the number of rows affected by the last SQL statement.
     * @return int
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    /**
     * Get the ID of the last inserted row.
     * @return string
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * Initiates a transaction.
     * @return bool
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commits a transaction.
     * @return bool
     */
    public function commit() {
        return $this->pdo->commit();
    }

    /**
     * Rolls back a transaction.
     * @return bool
     */
    public function rollBack() {
        return $this->pdo->rollBack();
    }
    
    /**
     * Checks if inside a transaction.
     * @return bool
     */
    public function inTransaction() {
        return $this->pdo->inTransaction();
    }

    /**
     * Closes the database connection.
     */
    public function close() {
        $this->pdo = null;
    }
}
?>