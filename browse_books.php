<?php include_once 'includes/header.php'; ?>

<main class="content-container">
    <h2 class="page-title">Browse Books</h2>

    <div class="search-container">
        <form action="#" method="get">
            <input type="text" name="search" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
    </div>

    <section class="book-grid">
      <?php
          // Query to fetch books with author details and quantity greater than 0
          $query = "SELECT books.book_id, books.book_name, books.quantity, books.price, authors.author_name 
                    FROM books 
                    INNER JOIN authors ON books.author_id = authors.author_id
                    WHERE books.quantity > 0"; // Modify the condition to quantity > 1 if needed
          $statement = $pdo->query($query);
          $books = $statement->fetchAll(PDO::FETCH_ASSOC);

          // Output each book as a card
          foreach ($books as $book) {
            echo '<div class="book-card">';
            echo '<img src="books/' . $book['book_id'] . '.jpg" alt="' . $book['book_name'] . '">';
            echo '<div class="book-details">';
            echo '<h3 class="book-title">' . $book['book_name'] . '</h3>';
            echo '<p class="book-author">by ' . $book['author_name'] . '</p>';
            echo '<p class="book-price">$' . number_format($book['price'], 2) . '</p>'; // Assuming there's a 'price' column in the books table
            
            // Form to add book to cart
            echo '<form action="add_to_cart.php" method="post">';
            echo '<input type="hidden" name="book_id" value="' . $book['book_id'] . '">';
            echo '<button type="submit" class="add-to-cart-btn">Add to Cart</button>';
            echo '</form>';
            
            echo '</div>';
            echo '</div>';
          }
        ?>
        
        <!-- Add more book cards as needed -->
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>
