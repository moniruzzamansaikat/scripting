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

    // Handle the /staffs route and fetch users
    public function index()
    {
        // Prepare and execute the SQL query to fetch all users from the users table
        $stmt = $this->pdo->prepare("SELECT first_name, last_name, email FROM users");
        $stmt->execute();

        // Fetch all results as an associative array
        $staffs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Check if we have results
        if ($staffs) {
            // Return the data as JSON
            header('Content-Type: application/json');
            echo json_encode($staffs, JSON_PRETTY_PRINT);
        } else {
            // If no users found, return an empty array
            echo json_encode([]);
        }
    }
}
