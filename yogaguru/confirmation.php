<?php
// Start the session only if it's not already active


// Include the database connection file
require_once 'config.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $fullName = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);
    $date = trim($_POST['date']);

    // Basic validation
    if (empty($fullName) || empty($email) || empty($phone) || empty($course) || empty($date)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: booking.php");
        exit();
    }

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO confirmation (full_name, email, phone, course, preferred_date) VALUES (:full_name, :email, :phone, :course, :preferred_date)");
    $stmt->bindParam(':full_name', $fullName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':course', $course);
    $stmt->bindParam(':preferred_date', $date);

    if ($stmt->execute()) {
        // Booking successful
        $_SESSION['success'] = "Your booking has been confirmed!";
    } else {
        // Insert failed
        $_SESSION['error'] = "Failed to confirm booking. Please try again.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect to the confirmation page
header("Location: confirmation.php");
exit();
?>
