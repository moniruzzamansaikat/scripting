<?php

namespace Src;

use PDO;
use PDOException;
use Exception;

class Database
{
    private $pdo;
    private static $instance;

    private function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname'),
                dbConfig('username'),
                dbConfig('password')
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the singleton instance.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Execute a query and return all results.
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Execute a query and return the number of affected rows.
     *
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Insert data into a table and return the last insert ID.
     *
     * @param string $table
     * @param array $data
     * @return int
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->execute($sql, $data);
        return $this->pdo->lastInsertId();
    }

    /**
     * Update records in a table.
     *
     * @param string $table
     * @param array $data
     * @param string $condition
     * @param array $params
     * @return int
     */
    public function update(string $table, array $data, string $condition, array $params): int
    {
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE $table SET $set WHERE $condition";
        return $this->execute($sql, array_merge($data, $params));
    }

    /**
     * Delete records from a table.
     *
     * @param string $table
     * @param string $condition
     * @param array $params
     * @return int
     */
    public function delete(string $table, string $condition, array $params): int
    {
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->execute($sql, $params);
    }

    /**
     * Count total rows in a table.
     *
     * @param string $table
     * @return int
     */
    public function count(string $table): int
    {
        $sql = "SELECT COUNT(*) FROM $table";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Select records with pagination.
     *
     * @param string $table
     * @param array $columns
     * @param int $perPage
     * @param int $currentPage
     * @return array
     */
    public function paginate(string $table, array $columns, int $perPage, int $currentPage): array
    {
        $offset = ($currentPage - 1) * $perPage;
        $columnsList = implode(',', $columns);
        $sql = "SELECT $columnsList FROM $table LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Find a single record by condition.
     *
     * @param string $table
     * @param string $condition
     * @param array $params
     * @return object|null
     */
    public function find(string $table, string $condition, array $params): ?object
    {
        $sql = "SELECT * FROM $table WHERE $condition LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

    /**
     * Find all records in a table.
     *
     * @param string $table
     * @return array
     */
    public function findAll(string $table): array
    {
        $sql = "SELECT * FROM $table";
        return $this->query($sql);
    }

    /**
     * Check if a record exists.
     *
     * @param string $table
     * @param string $condition
     * @param array $params
     * @return bool
     */
    public function exists(string $table, string $condition, array $params): bool
    {
        $sql = "SELECT 1 FROM $table WHERE $condition LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    /**
     * Begin a transaction.
     */
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction.
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * Rollback a transaction.
     */
    public function rollback(): void
    {
        $this->pdo->rollBack();
    }
}
