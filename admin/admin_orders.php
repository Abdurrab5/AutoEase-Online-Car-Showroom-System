<?php
include 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect if not logged in as admin
    exit;
}

// Fetch all orders
$order_query = "SELECT orders.*, cars.brand, cars.model, customers.name AS customer_name
                FROM orders
                INNER JOIN cars ON orders.car_id = cars.id
                INNER JOIN customers ON orders.customer_id = customers.id
                ORDER BY orders.order_date DESC";
$order_result = mysqli_query($conn, $order_query);

?>

<?php include 'header.php'; // Include admin header ?>

<div class="container mt-4">
    <h2>Manage Orders</h2>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Car</th>
                <th>Payment Type</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($order_result)): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['customer_name'] ?></td>
                    <td><?= $order['brand'] ?> <?= $order['model'] ?></td>
                    <td><?= $order['payment_type'] ?></td>
                    <td><?= $order['total_amount'] ?></td>
                    <td><?= $order['status'] ?></td>
                    <td><?= $order['order_date'] ?></td>
                    <td>
                        <a href="update_order_status.php?order_id=<?= $order['id'] ?>" class="btn btn-primary">Update Status</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; // Include admin footer ?>
