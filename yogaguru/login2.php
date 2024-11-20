<?php
session_start();

// Include the database connection file (config.php)
require_once 'config.php';  // Ensure this file is set up correctly

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: course.html");
    exit();
}

// Handle the form submission logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary POST keys exist
    if (isset($_POST['username'], $_POST['password'])) {
        // Sanitizing and assigning POST values
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Basic validation
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: index.html");
            exit();
        }

        // Query to find the user
        $stmt = $pdo->prepare("SELECT * FROM registration WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $user['password'];

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Login successful
                $_SESSION['username'] = $username; // Store username in session
                $_SESSION['loggedin'] = true; // Indicate user is logged in

                // Check if "Remember Me" is checked
                if (isset($_POST['remember_me'])) {
                    // Set a cookie that expires in 30 days
                    setcookie('remember_me', $username, time() + (30 * 24 * 60 * 60), "/"); // 30 days
                }

                header("Location: course.html"); // Redirect to welcome page
                exit();
            } else {
                // Invalid password
                $_SESSION['error'] = "Invalid password. Please try again.";
            }
        } else {
            // User not found
            $_SESSION['error'] = "User not found. Please check your username.";
        }

        // Redirect back to login page with error message
        header("Location: index.html");
        exit();
    } else {
        $_SESSION['error'] = "Form submission is incomplete.";
        header("Location: index.html");
        exit();
    }
}

// Check if the user has a "Remember Me" cookie
if (isset($_COOKIE['remember_me'])) {
    $username = $_COOKIE['remember_me'];

    // Set session variables
    $_SESSION['username'] = $username;
    $_SESSION['loggedin'] = true;

    // Optionally, you can retrieve the user's information from the database
    $stmt = $pdo->prepare("SELECT id FROM registration WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['id'] = $user['id'];
    }

    // Redirect to the welcome page
    header("Location: course.html");
    exit();
}
?>
<?php
session_start();

// Clear the session
$_SESSION = [];

// Destroy the session
session_destroy();

// Clear the cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/'); // Expire the cookie
}

// Redirect to login page
header("Location: index.html");
exit();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calm Aesthetic Login Page</title>
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
            color: #333;
        }

        /* Login Container Styling */
        .login-container {
            position: relative;
            width: 360px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 2;
        }

        /* Heading */
        h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #4a4a4a;
        }

        /* Input Styles */
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f7f7f7;
            color: #333;
            font-size: 1rem;
            outline: none;
        }

        input[type="text"]:focus, input[type="password"]:focus {
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
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #547fb1;
        }

        .error-message {
            color: red;
            font-size: 1rem;
            margin-bottom: 10px;
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
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Display Error Messages -->
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <!-- Login Form -->
        <form action="index.html" method="post">
            <input type="text" placeholder="Username" required name="username">
            <input type="password" placeholder="Password" required name="password">
            <input type="submit" value="Login">
        </form>

        <!-- Links -->
        <div class="options">
            <a href="registration.php">Don't have an account? Register</a> | <a href="forget.html">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
