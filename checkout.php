<?php
include_once 'includes/header.php';

// Function to calculate total price of items in cart
function calculateTotalPrice($pdo, $cart) {
    $totalPrice = 0;
    foreach ($cart as $book_id => $quantity) {
        // Retrieve price of each book from database
        $query = "SELECT price FROM books WHERE book_id = :book_id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':book_id', $book_id);
        $statement->execute();
        $book = $statement->fetch(PDO::FETCH_ASSOC);

        // Calculate total price
        $totalPrice += $book['price'] * $quantity;
    }
    return $totalPrice;
}

$totalPrice = isset($_SESSION['cart']) ? calculateTotalPrice($pdo, $_SESSION['cart']) : 0;
?>
<main class="content-container">
    <h2 class="page-title">Checkout</h2>

    <section class="checkout-section">
        <form action="place_order.php" method="post">
            <p>Please select a payment method:</p>
            <label>
                <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                Cash on Delivery
            </label>
            <label>
                <input type="radio" name="payment_method" value="card_payment">
                Card Payment
            </label>
            <input type="text" name="card_number" placeholder="Enter card number" class="card-number" disabled>
            <input type="text" name="address" placeholder="Enter address" class="address">
            <p>Total Price: $<?php echo number_format($totalPrice, 2); ?></p>
            <button type="submit">Proceed to Checkout</button>
        </form>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethodRadio = document.querySelectorAll('input[type="radio"][name="payment_method"]');
        const cardNumberInput = document.querySelector('.card-number');

        paymentMethodRadio.forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (this.value === 'card_payment') {
                    cardNumberInput.removeAttribute('disabled');
                } else {
                    cardNumberInput.setAttribute('disabled', 'disabled');
                }
            });
        });
    });
</script>


<?php include_once 'includes/footer.php'; ?>
