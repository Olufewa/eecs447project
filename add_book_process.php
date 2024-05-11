<?php
include_once 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $book_name = $_POST['book_name'];
    $book_author = $_POST['book_author'];
    $quantity = $_POST['quantity'];
    $price = $_POST['book_price'];

    // Check if author exists
    $author_query = "SELECT * FROM authors WHERE author_name = :author_name";
    $author_stmt = $pdo->prepare($author_query);
    $author_stmt->bindParam(':author_name', $book_author);
    $author_stmt->execute();
    $author_result = $author_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$author_result) {
        // Author does not exist, add to authors table
        $insert_author_query = "INSERT INTO authors (author_name) VALUES (:author_name)";
        $insert_author_stmt = $pdo->prepare($insert_author_query);
        $insert_author_stmt->bindParam(':author_name', $book_author);
        $insert_author_stmt->execute();
        $author_id = $pdo->lastInsertId();
    } else {
        // Author already exists
        $author_id = $author_result['author_id'];
    }

    // Check if the book already exists in the database
    $book_query = "SELECT * FROM books WHERE book_name = :book_name AND author_id = :author_id";
    $book_stmt = $pdo->prepare($book_query);
    $book_stmt->bindParam(':book_name', $book_name);
    $book_stmt->bindParam(':author_id', $author_id);
    $book_stmt->execute();
    $existing_book = $book_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_book) {
        // Book already exists, update its quantity and price
        $update_book_query = "UPDATE books SET quantity = quantity + :quantity, price = :price WHERE book_id = :book_id";
        $update_book_stmt = $pdo->prepare($update_book_query);
        $update_book_stmt->bindParam(':quantity', $quantity);
        $update_book_stmt->bindParam(':price', $price);
        $update_book_stmt->bindParam(':book_id', $existing_book['book_id']);
        $update_book_stmt->execute();
    } else {
        // Insert book into books table
        $insert_book_query = "INSERT INTO books (book_name, author_id, quantity, price) VALUES (:book_name, :author_id, :quantity, :price)";
        $insert_book_stmt = $pdo->prepare($insert_book_query);
        $insert_book_stmt->bindParam(':book_name', $book_name);
        $insert_book_stmt->bindParam(':author_id', $author_id);
        $insert_book_stmt->bindParam(':quantity', $quantity);
        $insert_book_stmt->bindParam(':price', $price);
        $insert_book_stmt->execute();
        
        // Get the ID of the newly inserted book
        $book_id = $pdo->lastInsertId();

        // Move uploaded file to 'books/' folder with the name as book ID
        $target_dir = "books/";
        $target_file = $target_dir . $book_id . ".jpg";
        move_uploaded_file($_FILES["book_image"]["tmp_name"], $target_file);        
    }
    
    
    // Redirect to admin panel
    header("Location: admin_panel.php");
    exit;
} else {
    // Invalid request method
    header("Location: error.php");
    exit;
}
?>
