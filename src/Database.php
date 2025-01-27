<?php

namespace Src;

use PDO;
use PDOException;

class Database
{
    private $pdo;
    private static $instance;

    private function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=testphp', 'admin', 'admin1');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }

    // Get the singleton instance
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Get the PDO connection
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    // Execute a query with optional parameters
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Execute a query and return the number of affected rows
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    // Insert data into a table
    public function insert(string $table, array $data): int
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->execute($sql, $data);
        return $this->pdo->lastInsertId();
    }

    // Update records in a table
    public function update(string $table, array $data, string $condition, array $params): int
    {
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE $table SET $set WHERE $condition";
        return $this->execute($sql, array_merge($data, $params));
    }

    // Delete records from a table
    public function delete(string $table, string $condition, array $params): int
    {
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->execute($sql, $params);
    }

    // Count total rows in a table
    public function count(string $table): int
    {
        $sql = "SELECT COUNT(*) FROM $table";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

    // Select records with pagination (limit and offset)
    public function select(string $table, array $columns, int $limit, int $offset): array
    {
        $columnsList = implode(',', $columns);
        $sql = "SELECT $columnsList FROM $table LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
