<?php
 
include 'header.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php'); // Redirect if not logged in as customer
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Fetch customer details
$customer_query = "SELECT * FROM customers WHERE id = $customer_id";
$customer_result = mysqli_query($conn, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);

// Fetch customer orders
$order_query = "SELECT orders.*, cars.brand, cars.model
                FROM orders
                INNER JOIN cars ON orders.car_id = cars.id
                WHERE orders.customer_id = $customer_id
                ORDER BY orders.order_date DESC";
$order_result = mysqli_query($conn, $order_query);
?>
 

<div class="container mt-4">
    <h2>Welcome, <?= $customer['name'] ?>!</h2>
    <p>Email: <?= $customer['email'] ?></p>
    <p>Phone: <?= $customer['phone'] ?></p>

    <h3>Order History</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Car</th>
                <th>Payment Type</th>
                <th>City</th>
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
                        <td>{$order['brand']} {$order['model']}</td>
                        <td>{$order['payment_type']}</td>
                        <td>{$order['delivery_city']}</td>
                        <td>{$order['total_amount']}</td>
                        <td>{$order['status']}</td>
                        <td>{$order['order_date']}</td>
                        <td>
                            <a href='view_order_details.php?order_id={$order['id']}' class='btn btn-info btn-sm'>View Details</a>
                        </td>
                    </tr>";
                $order_count++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; // Include customer footer ?>
