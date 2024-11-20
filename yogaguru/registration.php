<?php
// Ensure that the session is started only once
// Include the database connection file (config.php)
require_once 'config.php';  // Ensure this file is set up correctly

// Handle the form submission logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all necessary POST keys exist
    if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        // Sanitizing and assigning POST values
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
        $_SESSION['error'] = "Form submission is incomplete.";
        header("Location: registration.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calm Aesthetic Registration Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Basic Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, #e3eaf2, #f7f7f7);
            overflow: hidden;
            color: #333;
        }

        /* Registration Container Styling */
        .registration-container {
            position: relative;
            width: 360px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: box-shadow 0.3s ease;
            z-index: 2;
        }

        .registration-container:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
        }

        /* Heading */
        h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #4a4a4a;
        }

        /* Input Styles */
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f7f7f7;
            color: #333;
            font-size: 1rem;
            outline: none;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            background-color: #e3eaf2;
            border-color: #6b9cd8;
        }

        /* Submit Button */
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #6b9cd8;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #547fb1;
        }

        /* Links */
        .options {
            margin-top: 15px;
            font-size: 0.9rem;
            color: #6b9cd8;
        }

        .options a {
            color: #6b9cd8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .options a:hover {
            color: #547fb1;
        }

        /* Background Image */
        #dynamic-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
            z-index: -1;
        }

        .error-message {
            color: red;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            font-size: 1rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Default Background Image -->
    <img id="dynamic-bg" src="bg.gif" alt="Background Image">

    <!-- Registration Container -->
    <div class="registration-container">
        <h2>Register</h2>
        
        Display Error or Success Messages
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<div class='success-message'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        ?>

        <!-- Registration Form -->
        <form action="registration.php" method="post">
            <input type="text" placeholder="Username" required name="username">
            <input type="email" placeholder="Email" required name="email">
            <input type="password" placeholder="Password" required name="password">
            <input type="password" placeholder="Confirm Password" required name="confirm_password">
            <input type="submit" value="Register">
        </form>

        <!-- Links -->
        <div class="options">
            <a href="login2.php">Already have an account? Login</a> | <a href="forget.html">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
