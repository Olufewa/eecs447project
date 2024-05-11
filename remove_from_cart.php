<?php
session_start();
include_once 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // Check if the book is in the cart
    if (isset($_SESSION['cart'][$book_id])) {
        // Reduce the quantity by 1
        $_SESSION['cart'][$book_id]--;
        
        // If the quantity becomes 0, remove the book from the cart
        if ($_SESSION['cart'][$book_id] == 0) {
            unset($_SESSION['cart'][$book_id]);
        }

        // Redirect back to the cart page
        header("Location: cart.php");
        exit;
    } else {
        // If the book is not in the cart, redirect to an error page
        header("Location: error.php");
        exit;
    }
} else {
    // If the request method is not POST or book_id is not set, redirect to an error page
    header("Location: error.php");
    exit;
}
?>
