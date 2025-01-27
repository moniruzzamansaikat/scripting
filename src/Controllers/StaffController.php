<?php

namespace Src\Controllers;

use Src\Database;
use Src\Response;

class StaffController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 10;

        $offset = ($page - 1) * $limit;

        try {
            $totalUsers = $this->db->count('users');

            $staffs = $this->db->select('users', ['first_name', 'last_name', 'email'], $limit, $offset);

            Response::paginated($staffs, $page, $limit, $totalUsers);
        } catch (\Exception $e) {
            Response::error($e->getMessage());
        }
    }
}
