<?php
include 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect if not logged in as admin
    exit;
}

 

// Fetch all orders from the database
$order_query = "SELECT orders.*, customers.name AS customer_name, cars.brand, cars.model
                FROM orders
                INNER JOIN customers ON orders.customer_id = customers.id
                INNER JOIN cars ON orders.car_id = cars.id
                ORDER BY orders.order_date DESC";
$order_result = mysqli_query($conn, $order_query);

?>

<div class="container mt-4">
    <h2>All Customer Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Car</th>
                <th>Payment Type</th>
                <th>City</th>
                <th>Delivery Addres</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $order_count = 1;
            while ($order = mysqli_fetch_assoc($order_result)) {
                echo "<tr>
                        <td>{$order_count}</td>
                        <td>{$order['customer_name']}</td>
                        <td>{$order['brand']} {$order['model']}</td>
                        <td>{$order['payment_type']}</td>
                        <td>{$order['delivery_city']}</td>
                         <td>{$order['delivery_address']}</td>
                        <td>{$order['total_amount']}</td>
                        <td>{$order['status']}</td>
                        <td>{$order['order_date']}</td>
                        <td>
                            <a href='update_order.php?order_id={$order['id']}' class='btn btn-primary btn-sm'>Update Status</a>
                        </td>
                    </tr>";
                $order_count++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
