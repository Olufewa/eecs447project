<?php
include_once 'includes/db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL to insert admin into users table
    $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'admin')";

    // Prepare statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute statement
    if ($stmt->execute()) {
        // Redirect back to admin panel
        header("Location: admin_panel.php");
        exit();
    } else {
        // Error handling
        echo "Error: Unable to add admin.";
    }
}
?>
