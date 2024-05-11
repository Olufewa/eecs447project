<?php include_once 'includes/header.php'; ?>

<main class="content-container">
    <h2 class="page-title">Your Cart</h2>

    <section class="cart-items">
        <?php
        // Check if cart is not empty
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            // Retrieve book details from the database based on the book IDs in the cart
            $cart_books = [];
            $cart_book_ids = array_keys($_SESSION['cart']);
            $cart_book_ids_str = implode(',', $cart_book_ids);
            $query = "SELECT book_id, book_name, author_name, price FROM books INNER JOIN authors ON books.author_id = authors.author_id WHERE book_id IN ($cart_book_ids_str)";
            $statement = $pdo->query($query);
            $cart_books_data = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Display each book in the cart along with its quantity and remove button
            foreach ($cart_books_data as $book) {
                $book_id = $book['book_id'];
                $quantity = $_SESSION['cart'][$book_id];
                echo '<div class="cart-item">';
                echo '<img src="books/' . $book_id . '.jpg" alt="' . $book['book_name'] . '" class="cart-item-image">';
                echo '<div class="cart-item-details">';
                echo '<h3 class="cart-item-title">' . $book['book_name'] . '</h3>';
                echo '<p class="cart-item-author">by ' . $book['author_name'] . '</p>';
                echo '<p class="cart-item-price">$' . number_format($book['price'], 2) . '</p>';
                echo '<p class="cart-item-quantity">Quantity: ' . $quantity . '</p>';
                // Form to remove item from cart
                echo '<form action="remove_from_cart.php" method="post">';
                echo '<input type="hidden" name="book_id" value="' . $book_id . '">';
                echo '<button type="submit" class="remove-from-cart-btn">Remove</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }

            // Checkout button
            echo '<div class="checkout-button">';
            if (isset($_SESSION['logged_in'])) {
                echo '<a href="checkout.php" class="button">Checkout</a>';
            } else {
                echo '<a href="login.php" class="button">Login to Checkout</a>';
            }
            echo '</div>';
        } else {
            // Display a message if cart is empty
            echo '<p class="empty-cart">Your cart is empty.</p>';
        }
        ?>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>
