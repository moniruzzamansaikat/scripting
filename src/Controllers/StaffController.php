<?php

namespace Src\Controllers;

class StaffController
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct()
    {
        try {
            // Set up the PDO connection
            $this->pdo = new \PDO('mysql:host=localhost;dbname=testphp', 'admin', 'admin1');
            // Set the PDO error mode to exception for error handling
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            // Handle connection error
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }

    // Handle the /staffs route with pagination
    public function index()
    {
        // Get pagination parameters from query string
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        // Ensure valid values
        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 10;

        $offset = ($page - 1) * $limit;

        try {
            // Count total users
            $totalStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM users");
            $totalStmt->execute();
            $totalUsers = $totalStmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Prepare and execute the paginated query
            $stmt = $this->pdo->prepare("SELECT first_name, last_name, email FROM users LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();

            // Fetch paginated results as an associative array
            $staffs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Prepare the response
            $response = [
                'page'       => $page,
                'limit'      => $limit,
                'total'      => (int)$totalUsers,
                'totalPages' => ceil($totalUsers / $limit),
                'data'       => $staffs
            ];

            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($response, JSON_PRETTY_PRINT);
        } catch (\PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
