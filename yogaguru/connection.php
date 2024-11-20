<?php
session_start();

// Database connection
try {
    $pdo = new PDO('mysql:host=your_host;dbname=your_db', 'your_username', 'your_password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging line
    echo "Form submitted. Processing...<br>";

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: registration.php");
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: registration.php");
        exit();
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $_SESSION['error'] = "Username must be between 3 and 20 characters.";
        header("Location: registration.php");
        exit();
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: registration.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT * FROM registration WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Username or email already exists.";
        header("Location: registration.php");
        exit();
    }

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO registration (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username; 
        header("Location: welcome.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: registration.php");
        exit();
    }
} else {
    echo "Invalid request method."; // Debugging line
}
?>
