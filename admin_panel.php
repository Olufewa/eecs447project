<?php include_once 'includes/header.php'; ?>

<main class="admin-panel">
    <h2 class="admin-panel-title">Admin Panel - Orders</h2>
    <table class="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Address</th>
                <th>Total Amount</th>
                <th>Date</th>
                <th>Items Ordered</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include database connection
            include_once 'includes/db_connection.php';

            // Query to fetch order details including username, address, and items ordered
            $query = "SELECT orders.order_id, users.username, orders.address, SUM(order_items.quantity * books.price) AS total_amount, orders.order_date, GROUP_CONCAT(books.book_name SEPARATOR ', ') AS items_ordered
                      FROM orders 
                      INNER JOIN users ON orders.user_id = users.id 
                      INNER JOIN order_items ON orders.order_id = order_items.order_id 
                      INNER JOIN books ON order_items.book_id = books.book_id
                      GROUP BY orders.order_id";
            $statement = $pdo->query($query);
            $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Output each order row
            foreach ($orders as $order) {
                echo '<tr>';
                echo '<td class="order-id">' . $order['order_id'] . '</td>';
                echo '<td class="username">' . $order['username'] . '</td>';
                echo '<td class="address">' . $order['address'] . '</td>';
                echo '<td class="total-amount">$' . number_format($order['total_amount'], 2) . '</td>';
                echo '<td class="order-date">' . $order['order_date'] . '</td>';
                echo '<td class="items-ordered">' . $order['items_ordered'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</main>

<?php include_once 'includes/footer.php'; ?>
