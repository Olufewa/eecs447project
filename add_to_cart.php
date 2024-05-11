<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    
    // Add the book to the cart or increment its quantity
    if (isset($_SESSION['cart'][$book_id])) {
        // Book already exists in cart, increment quantity
        $_SESSION['cart'][$book_id]++;
    } else {
        // Book doesn't exist in cart, add it with quantity of 1
        $_SESSION['cart'][$book_id] = 1;
    }

    // Redirect back to the previous page or wherever appropriate
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
} else {
    // Invalid request method or missing book ID
    header("Location: error.php");
    exit;
}
?>
