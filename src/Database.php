<?php

namespace Src;

use PDO;
use PDOException;
use Exception;

class Database
{
    private $pdo;
    private static $instance;

    private $query;
    private $params;

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

            // Initialize the query and parameters for builder pattern
            $this->query = '';
            $this->params = [];
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
     * Initialize query with a base select statement.
     *
     * @param string $table
     * @param array $columns
     * @return self
     */
    public function from(string $table, array $columns = ['*']): self
    {
        $columnsList = implode(',', $columns);
        $this->query = "SELECT $columnsList FROM $table";
        return $this;
    }

    /**
     * Add LIMIT clause.
     *
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->query .= " LIMIT $limit"; // Directly interpolate the limit value
        return $this;
    }

    /**
     * Add OFFSET clause.
     *
     * @param int $offset
     * @return self
     */
    public function offset(int $offset): self
    {
        $this->query .= " OFFSET $offset"; // Directly interpolate the offset value
        return $this;
    }

    /**
     * Add SKIP clause (same as OFFSET in SQL).
     *
     * @param int $skip
     * @return self
     */
    public function skip(int $skip): self
    {
        return $this->offset($skip);
    }

    public function where(string $column, $operator = '=', $value = null): self
    {
        $this->query .= " WHERE $column $operator :$column";
        $this->params = array_merge($this->params, ["$column" => $value]);
        return $this;
    }

    /**
     * Execute the query and return the results.
     *
     * @return array
     */
    public function get(): array
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->fetchAll();
    }

    
    public function first()
    {
        return $this->get()[0] ?? null;
    }

    /**
     * Count the number of rows matching the query.
     *
     * @return int
     */
    public function count(): int
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }

    /**
     * Insert a record into a table.
     *
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function insert(string $table, array $data): bool
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $this->query = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        // Bind values dynamically
        $this->params = array_combine(
            array_map(fn($key) => ':' . $key, array_keys($data)),
            array_values($data)
        );

        $stmt = $this->pdo->prepare($this->query);
        return $stmt->execute($this->params);
    }

    /**
     * Update records in a table.
     *
     * @param string $table
     * @param array $data
     * @param string $condition
     * @param array $params
     * @return bool
     */
    public function update(string $table, array $data, string $condition, array $params = []): bool
    {
        $setClause = implode(',', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $this->query = "UPDATE $table SET $setClause WHERE $condition";

        // Bind values dynamically
        $this->params = array_merge(
            array_combine(array_map(fn($col) => ":$col", array_keys($data)), array_values($data)),
            $params
        );

        $stmt = $this->pdo->prepare($this->query);
        return $stmt->execute($this->params);
    }

    /**
     * Delete records from a table.
     *
     * @param string $table
     * @param string $condition
     * @param array $params
     * @return bool
     */
    public function delete(string $table, string $condition, array $params = []): bool
    {
        $this->query = "DELETE FROM $table WHERE $condition";
        $this->params = $params;

        $stmt = $this->pdo->prepare($this->query);
        return $stmt->execute($this->params);
    }
}
