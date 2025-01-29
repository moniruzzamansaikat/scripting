<?php

namespace Src\Models;

use Src\Database;
use Exception;

class Model
{
    protected $table = null; // Table name
    protected $primaryKey = 'id'; // Primary key column
    protected $fillable = []; // Fields that can be mass-assigned
    protected $hidden = []; // Fields to hide when converting to array/json
    private $limit = 1000000000;

    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();

        // Automatically determine the table name if not provided
        if (!$this->table) {
            $className = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower($className) . 's'; // Pluralize the class name
        }
    }

    /**
     * Get all records from the table.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->db->from($this->table)->get();
    }

    /**
     * Find a record by its primary key.
     *
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        return $this->db->from($this->table)
            ->where("{$this->primaryKey} = :id", [':id' => $id])
            ->get()[0] ?? null;
    }

    /**
     * Create a new record in the table.
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        // Filter data to only include fillable fields
        $filteredData = array_intersect_key($data, array_flip($this->fillable));

        return $this->db->insert($this->table, $filteredData);
    }

    /**
     * Update a record in the table.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        // Filter data to only include fillable fields
        $filteredData = array_intersect_key($data, array_flip($this->fillable));

        return $this->db->update(
            $this->table,
            $filteredData,
            "{$this->primaryKey} = :id",
            [':id' => $id]
        );
    }

    /**
     * Delete a record from the table.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = :id",
            [':id' => $id]
        );
    }

    /**
     * Get records with a WHERE clause.
     *
     * @param string $condition
     * @param array $params
     * @return array
     */
    public function where(string $condition, array $params = []): array
    {
        return $this->db->from($this->table)
            ->where($condition, $params)
            ->get();
    }

    /**
     * Get the first record matching the condition.
     *
     * @param string $condition
     * @param array $params
     * @return object|null
     */
    public function first(string $condition, array $params = []): ?object
    {
        return $this->db->from($this->table)
            ->where($condition, $params)
            ->limit(1)
            ->get()[0] ?? null;
    }

    /**
     * Apply a limit to the query.
     *
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        $this->db->limit($limit);
        return $this;
    }

    /**
     * Apply an offset to the query.
     *
     * @param int $offset
     * @return self
     */
    public function offset(int $offset): self
    {
        $this->db->offset($offset);
        return $this;
    }

    /**
     * Apply a skip to the query (same as offset).
     *
     * @param int $skip
     * @return self
     */
    public function skip(int $skip): self
    {
        return $this->offset($skip);
    }

    /**
     * Count the records matching the query.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->db->count();
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        foreach ($this->fillable as $field) {
            if (isset($this->$field)) {
                $data[$field] = $this->$field;
            }
        }
        return array_diff_key($data, array_flip($this->hidden)); // Hide specified fields
    }

    /**
     * Convert the model instance to JSON.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Define a one-to-one relationship.
     *
     * @param string $model
     * @param string $foreignKey
     * @param string $localKey
     * @return object|null
     */
    public function hasOne(string $model, string $foreignKey = '', string $localKey = 'id'): ?object
    {
        $relatedModel = new $model();
        $foreignKey = $foreignKey ?: $this->table . '_id';
        return $relatedModel->first("$foreignKey = :localKey", [':localKey' => $this->$localKey]);
    }

    /**
     * Define a one-to-many relationship.
     *
     * @param string $model
     * @param string $foreignKey
     * @param string $localKey
     * @return array
     */
    public function hasMany(string $model, string $foreignKey = '', string $localKey = 'id'): array
    {
        $relatedModel = new $model();
        $foreignKey = $foreignKey ?: $this->table . '_id';
        return $relatedModel->where("$foreignKey = :localKey", [':localKey' => $this->$localKey]);
    }

    /**
     * Finalize the query and fetch the results.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->db->from($this->table)->limit($this->limit)->get();
    }
}
