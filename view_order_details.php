<?php
 
include 'header.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php'); // Redirect if not logged in as customer
    exit;
}

if (!isset($_GET['order_id'])) {
    echo "Invalid order ID.";
    exit;
}

$order_id = intval($_GET['order_id']);
$customer_id = $_SESSION['customer_id'];

// Fetch order details
$order_query = "SELECT orders.*, cars.brand, cars.model
                FROM orders
                INNER JOIN cars ON orders.car_id = cars.id
                WHERE orders.id = $order_id AND orders.customer_id = $customer_id";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "Order not found.";
    exit;
}

$order = mysqli_fetch_assoc($order_result);
?>

 
<div class="container mt-4">
    <h2>Order Details</h2>
    <p><strong>Car:</strong> <?= $order['brand'] ?> <?= $order['model'] ?></p>
    <p><strong>Payment Type:</strong> <?= $order['payment_type'] ?></p>
    <p><strong>Delivery City:</strong> <?= $order['delivery_city'] ?></p>
    <p><strong>Total Amount:</strong> <?= $order['total_amount'] ?></p>
    <p><strong>Status:</strong> <?= $order['status'] ?></p>
    <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>

    <h3>Payment Status</h3>
    <p>Status: <?= $order['payment_status'] ?></p>
    <p>Payment Due: <?= $order['payment_status'] == 'Pending' ? 'Yes' : 'No' ?></p>

    <h3>Delivery Information</h3>
    <p>Delivery Charge: <?= $order['delivery_charge'] ?></p>

    <h3>Actions</h3>
    <a href="cancel_order.php?order_id=<?= $order['id'] ?>" class="btn btn-danger btn-sm">Cancel Order</a>
</div>

<?php include 'footer.php'; // Include customer footer ?>
