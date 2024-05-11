<?php include_once 'includes/header.php'; ?>

<main class="add-book-container">
    <h2 class="add-book-title">Add Book</h2>
    <form action="add_book_process.php" method="post" enctype="multipart/form-data" class="add-book-form">
        <label for="book_image" class="add-book-label">Book Image:</label><br>
        <input type="file" id="book_image" name="book_image" required accept="image/*" class="add-book-input"><br>

        <label for="book_name" class="add-book-label">Book Name:</label><br>
        <input type="text" id="book_name" name="book_name" required class="add-book-input"><br>

        <label for="book_author" class="add-book-label">Book Author:</label><br>
        <input type="text" id="book_author" name="book_author" required class="add-book-input"><br>

        <label for="book_price" class="add-book-label">Book Price:</label><br>
        <input type="number" id="book_price" name="book_price" required class="add-book-input" step="0.01" min="0"><br>

        <label for="quantity" class="add-book-label">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity" required class="add-book-input" min="1"><br>

        <button type="submit" class="add-book-button">Add Book</button>
    </form>
</main>

<?php include_once 'includes/footer.php'; ?>
