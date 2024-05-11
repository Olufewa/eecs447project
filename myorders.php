<?php include_once 'includes/header.php'; ?>

<main class="content-container">
    <h2 class="page-title">Your Orders</h2>

    <div class="order-container">
        <?php
        include_once 'includes/db_connection.php';

        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            // Get user ID from the database
            $user_query = "SELECT id FROM users WHERE username = :username";
            $user_stmt = $pdo->prepare($user_query);
            $user_stmt->bindParam(':username', $username);
            $user_stmt->execute();
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $user_id = $user['id'];

                // Get order IDs for the user
                $order_query = "SELECT order_id FROM orders WHERE user_id = :user_id";
                $order_stmt = $pdo->prepare($order_query);
                $order_stmt->bindParam(':user_id', $user_id);
                $order_stmt->execute();
                $order_ids = $order_stmt->fetchAll(PDO::FETCH_COLUMN);

                if ($order_ids) {
                    // Loop through each order ID
                    foreach ($order_ids as $order_id) {
                        // Get order details
                        $order_details_query = "SELECT * FROM orders WHERE order_id = :order_id";
                        $order_details_stmt = $pdo->prepare($order_details_query);
                        $order_details_stmt->bindParam(':order_id', $order_id);
                        $order_details_stmt->execute();
                        $order = $order_details_stmt->fetch(PDO::FETCH_ASSOC);

                        if ($order) {
                            echo '<div class="order-card">';
                            echo '<h3 class="order-id">Order ID: ' . $order['order_id'] . '</h3>';
                            echo '<p class="order-date">Order Date: ' . $order['order_date'] . '</p>';
                            echo '<p class="order-price">Order Price: $' . number_format($order['order_price'], 2) . '</p>';

                            // Get order items
                            $order_items_query = "SELECT order_items.quantity, books.book_name, books.price 
                                                  FROM order_items 
                                                  INNER JOIN books ON order_items.book_id = books.book_id 
                                                  WHERE order_items.order_id = :order_id";
                            $order_items_stmt = $pdo->prepare($order_items_query);
                            $order_items_stmt->bindParam(':order_id', $order_id);
                            $order_items_stmt->execute();
                            $order_items = $order_items_stmt->fetchAll(PDO::FETCH_ASSOC);

                            echo '<ul class="order-items">';
                            foreach ($order_items as $item) {
                                echo '<li class="order-item">' . $item['quantity'] . ' x ' . $item['book_name'] . ' - $' . number_format($item['price'], 2) . '</li>';
                            }
                            echo '</ul>';

                            echo '</div>';
                        }
                    }
                } else {
                    echo '<p class="no-orders">No orders found.</p>';
                }
            } else {
                echo '<p class="user-not-found">User not found.</p>';
            }
        } else {
            echo '<p class="not-logged-in">Please login to view your orders.</p>';
        }
        ?>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
