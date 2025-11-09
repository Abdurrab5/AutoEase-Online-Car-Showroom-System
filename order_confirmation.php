<?php
 
include 'header.php';

if (!isset($_SESSION['order_id'])) {
    echo "No order found.";
    include 'footer.php';
    exit;
}

$order_id = $_SESSION['order_id'];

$order_message = $_SESSION['order_message'];

// Retrieve order details
$order_query = "SELECT o.*, c.brand, c.model, c.price, c.image
                FROM orders o
                JOIN cars c ON o.car_id = c.id
                WHERE o.id = $order_id LIMIT 1";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "Order not found.";
    include 'footer.php';
    exit;
}

$order = mysqli_fetch_assoc($order_result);

// Display order confirmation
?>
<div class="container mt-4">
    <h2>Order Confirmation</h2>
    <p><strong>Order ID:</strong> <?= $order_id ?></p>
    <p><?= $order_message ?></p>

    <div class="row">
        <div class="col-md-6">
            <img src="uploads/<?= $order['image'] ?>" class="img-fluid" style="max-height: 300px;">
        </div>
        <div class="col-md-6">
            <h4><?= $order['brand'] . ' ' . $order['model'] ?></h4>
            <p><strong>Price:</strong> PKR <?= number_format($order['price']) ?></p>
            <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_type']) ?></p>
            <p><strong>Delivery City:</strong> <?= $order['delivery_city'] ?></p>
            <p><strong>Delivery Address:</strong> <?= nl2br($order['delivery_address']) ?></p>

            <p><strong>Total Amount:</strong> PKR <?= number_format($order['total_amount']) ?></p>

            <p><strong>Status:</strong> <?=$order_status = $order['status'];
 ?></p>

            <a href="index.php" class="btn btn-primary">Go to Homepage</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
