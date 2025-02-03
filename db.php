<?php
// db.php - Database connection handler
// This file sets up a connection to the MySQL database using MySQLi.
// Adjust the connection parameters as needed for different environments (development, staging, production).

// Database connection parameters
$host = "localhost";      // Hostname for the MySQL server. Change as required for your environment.
$user = "root";           // MySQL username. Update with your MySQL username.
$password = "";           // MySQL password. Update with your MySQL password.
$dbname = "love_meter_db"; // Name of the MySQL database. Ensure the database exists and adjust if needed.

// Create a new MySQLi connection
$connection = new mysqli($host, $user, $password, $dbname);

// Check if the connection is successful; if not, output an error message and stop execution.
if ($connection->connect_error) {
    die("Database connection failed: " . $connection->connect_error);
}

// The connection is successfully established and is available via the $connection variable.
// For different environments, consider using environment variables or configuration files to manage these parameters for security.
?>
