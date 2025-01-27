<?php
// Database connection details
$host = 'localhost';
$dbname = 'testphp';
$username = 'admin';
$password = 'admin1';

// Connect to MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch user data
$sql = "SELECT id, first_name, last_name, email, phone, age, gender, address, created_at FROM users";
$result = $conn->query($sql);

// Check if the query returned results
if ($result->num_rows > 0) {
    // Output data as an HTML table
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Created At</th>
            </tr>";

    // Fetch and display each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['first_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['age']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['address']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}

// Close the database connection
$conn->close();
