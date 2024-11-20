<?php
$host = 'localhost';     // MySQL host (localhost for local server)
$username = 'root';      // Your MySQL username (default is 'root' for many installations)
$password = '';          // Your MySQL password (default is an empty string for many local installations)
$dbname = 'yoga_guru';     // The name of the database you want to connect to

// Create a PDO connection string
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    // Attempt to create a new PDO connection
    $pdo = new PDO($dsn, $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If connection is successful, output a success message
    echo "Connected to the database '$dbname' successfully!";
} catch (PDOException $e) {
    // If connection fails, display the error message
    echo "Connection failed: " . $e->getMessage();
}
?>
