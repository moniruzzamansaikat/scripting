<?php

namespace Src\Controllers;

use Src\Database;

class StaffController
{
    private $db;

    // Constructor to initialize the Database connection
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Handle the /staffs route with pagination
    public function index()
    {
        echo '1';
        
        
        // Get pagination parameters from query string
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        // Ensure valid values
        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 10;

        $offset = ($page - 1) * $limit;

        try {
            // Count total users
            $totalUsers = $this->db->count('users');

            // Fetch paginated results
            $staffs = $this->db->select('users', ['first_name', 'last_name', 'email'], $limit, $offset);

            // Prepare the response
            $response = [
                'page' => $page,
                'limit' => $limit,
                'total' => (int)$totalUsers,
                'totalPages' => ceil($totalUsers / $limit),
                'data' => $staffs
            ];

            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($response, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
