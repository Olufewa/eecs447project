<?php
session_start();
include_once 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Define the default role for new users
    $role = "user";

    // Prepare and execute the SQL query to insert data into the users table
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $hashed_password, $role]);

    // Set session variables for the logged-in user
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
    $_SESSION['logged_in'] = true;

    // Redirect the user to the browse_books.php page after successful registration
    header("Location: browse_books.php");
    exit();
}
?>
