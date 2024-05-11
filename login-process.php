<?php
session_start();
include_once 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables for the logged-in user
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        // Redirect based on user role
        if ($user['role'] === 'admin') {
            header("Location: admin_panel.php");
        } else {
            header("Location: browse_books.php");
        }
        exit();
    } else {
        // If login fails, redirect back to login page with error message
        header("Location: login.php?error=1");
        exit();
    }
}
?>
