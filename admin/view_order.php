<?php 
include 'header.php';

if (!isset($_GET['order_id'])) {
    echo "Invalid order ID.";
    include 'footer.php';
    exit;
}

$order_id = intval($_GET['order_id']);

// Retrieve order details
$order_query = "SELECT o.*, c.brand, c.model, c.price, c.image, cu.name as customer_name, cu.email as customer_email
                FROM orders o
                JOIN cars c ON o.car_id = c.id
                JOIN customers cu ON o.customer_id = cu.id
                WHERE o.id = $order_id LIMIT 1";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "Order not found.";
    include 'footer.php';
    exit;
}

$order = mysqli_fetch_assoc($order_result);

?>
<div class="container mt-4">
    <h2>Order Details - Order ID: <?= $order_id ?></h2>
    
    <div class="row">
        <div class="col-md-6">
            <img src="uploads/<?= $order['image'] ?>" class="img-fluid" style="max-height: 300px;">
        </div>
        <div class="col-md-6">
            <h4><?= $order['brand'] . ' ' . $order['model'] ?></h4>
            <p><strong>Customer:</strong> <?= $order['customer_name'] ?> (<?= $order['customer_email'] ?>)</p>
            <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_type']) ?></p>
            <p><strong>Total Amount:</strong> PKR <?= number_format($order['total_amount']) ?></p>
            <p><strong>Status:</strong> <?= $order['status'] ?></p>
            <p><strong>Delivery City:</strong> <?= $order['delivery_city'] ?></p>
            <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>
            <p><strong>Address:</strong> <?= nl2br($order['delivery_address']) ?></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
