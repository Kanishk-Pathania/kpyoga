<?php
session_start();

// Include the database connection
require_once 'config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_username = trim($_POST['username']);
    $entered_password = $_POST['password'];

    // Query to find the user
    $stmt = $pdo->prepare("SELECT * FROM registration WHERE username = :username");
    $stmt->bindParam(':username', $entered_username);
    
    try {
        $stmt->execute();
    } catch (Exception $e) {
        die("Query failed: " . $e->getMessage());
    }

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $user['password'];

        // Verify the password
        if (password_verify($entered_password, $hashed_password)) {
            // Login successful
            $_SESSION['username'] = $entered_username; // Store username in session
            header("Location:course.html"); // Redirect to welcome page
            exit();
        } else {
            // Invalid password
            $_SESSION['login_error'] = "Invalid password. Please try again.";
        }
    } else {
        // User not found
        $_SESSION['login_error'] = "User not found. Please check your username.";
    }

    // Redirect back to login page with error message
    header("Location: login2.php");
    exit();
} else {
    echo "Access denied. Please submit the form."; 
}
?>
