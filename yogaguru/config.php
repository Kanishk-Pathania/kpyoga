<?php
session_start(); // Start the session to use session variables

// Database configuration
$servername = "localhost"; // Database server (usually localhost)
$username = "root"; // Your database username
$password = ""; // Your database password (usually empty for XAMPP)
$dbname = "yoga_guru"; // Your database name
$charset = "utf8mb4"; // Character set for MySQL (utf8mb4 is recommended for full Unicode support)

try {
    // Set up the DSN (Data Source Name) for PDO
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
    
    // Create a new PDO instance for MySQL connection
    $pdo = new PDO($dsn, $username, $password);
    
    // Set the PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Success message
    echo "Database connection successful!";

} catch (PDOException $e) {
    // Handle connection failure
    echo "Database connection failed: " . $e->getMessage();
    exit();
}