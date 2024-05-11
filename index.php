<?php
include_once 'includes/header.php';
?>

<main class="content-container">
    <section id="featured-books">
        <h2>Featured Books</h2>
        <?php
          // Query to fetch books with author details and quantity greater than 0
          $query = "SELECT books.book_id, books.book_name
                    FROM books 
                    WHERE books.quantity > 0
                    LIMIT 3"; // Modify the condition to quantity > 1 if needed
          $statement = $pdo->query($query);
          $books = $statement->fetchAll(PDO::FETCH_ASSOC);

          // Output each book as a card
          foreach ($books as $book) {
            echo '<div class="book"><a href="browse_books.php">';
              echo '<img src="books/'.$book['book_id'].'.jpg" alt="Book '.$book['book_id'].'">';
              echo '<p>'.$book['book_name'].'</p>';
            echo '</a></div>';          
          }
        ?>
    </section>

</main>

<?php
include_once 'includes/footer.php';
?>
