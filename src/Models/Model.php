<?php

namespace Src\Models;

use Src\Database;

class Model
{
    protected $table  = null;

    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();

        if (!$this->table) {
            $className = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower($className) . 's';
        }
    }

    public function all()
    {
        return $this->db->findAll($this->table);
    }
}
