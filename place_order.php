<?php
session_start();
include_once 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username from session
    $username = $_SESSION['username'];

    // Check if username exists
    $user_query = "SELECT id FROM users WHERE username = :username";
    $user_stmt = $pdo->prepare($user_query);
    $user_stmt->bindParam(':username', $username);
    $user_stmt->execute();
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user ID is found
    if (!$user) {
        // Redirect or display error message if user ID is not found
        header("Location: error.php");
        exit;
    }
    $user_id = $user['id'];

    // Get current date and time
    $order_date = date('Y-m-d H:i:s');

    // Get address from POST data
    $address = $_POST['address'];

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Calculate total price of the order
        $total_price = 0;

        foreach ($_SESSION['cart'] as $book_id => $quantity) {
            // Retrieve book details from the database
            $book_query = "SELECT * FROM books WHERE book_id = :book_id";
            $book_stmt = $pdo->prepare($book_query);
            $book_stmt->bindParam(':book_id', $book_id);
            $book_stmt->execute();
            $book = $book_stmt->fetch(PDO::FETCH_ASSOC);

            // Calculate total price for the item
            $total_price += $book['price'] * $quantity;
        }

        // Insert order into orders table with address
        $insert_order_query = "INSERT INTO orders (user_id, order_date, order_price, address) VALUES (:user_id, :order_date, :order_price, :address)";
        $insert_order_stmt = $pdo->prepare($insert_order_query);
        $insert_order_stmt->bindParam(':user_id', $user_id);
        $insert_order_stmt->bindParam(':order_date', $order_date);
        $insert_order_stmt->bindParam(':order_price', $total_price);
        $insert_order_stmt->bindParam(':address', $address);
        $insert_order_stmt->execute();

        // Get the ID of the newly inserted order
        $order_id = $pdo->lastInsertId();

        // Insert items into order_items table
        foreach ($_SESSION['cart'] as $book_id => $quantity) {
            // Retrieve book details from the database
            $book_query = "SELECT * FROM books WHERE book_id = :book_id";
            $book_stmt = $pdo->prepare($book_query);
            $book_stmt->bindParam(':book_id', $book_id);
            $book_stmt->execute();
            $book = $book_stmt->fetch(PDO::FETCH_ASSOC);

            // Deduct quantity from book stock
            $new_quantity = $book['quantity'] - $quantity;
            if ($new_quantity < 0) {
                // Rollback transaction and display error message
                $pdo->rollBack();
                echo "Failed to place order: Insufficient stock for one or more items.";
                exit;
            }

            // Insert item into order_items table
            $insert_item_query = "INSERT INTO order_items (order_id, book_id, quantity) VALUES (:order_id, :book_id, :quantity)";
            $insert_item_stmt = $pdo->prepare($insert_item_query);
            $insert_item_stmt->bindParam(':order_id', $order_id);
            $insert_item_stmt->bindParam(':book_id', $book_id);
            $insert_item_stmt->bindParam(':quantity', $quantity);
            $insert_item_stmt->execute();

            // Update quantity in books table
            $update_quantity_query = "UPDATE books SET quantity = :new_quantity WHERE book_id = :book_id";
            $update_quantity_stmt = $pdo->prepare($update_quantity_query);
            $update_quantity_stmt->bindParam(':new_quantity', $new_quantity);
            $update_quantity_stmt->bindParam(':book_id', $book_id);
            $update_quantity_stmt->execute();
        }

        // Commit transaction
        $pdo->commit();

        // Clear the cart after successful order placement
        unset($_SESSION['cart']);

        // Redirect to thank you page
        header("Location: thankyou.php");
        exit;
    } catch (Exception $e) {
        // Rollback transaction and display error message
        $pdo->rollBack();
        echo "Failed to place order: " . $e->getMessage();
    }
} else {
    // Invalid request method
    header("Location: error.php");
    exit;
}
?>
