<?php
include 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect if not logged in as admin
    exit;
}

if (!isset($_GET['order_id'])) {
    echo "Invalid order ID.";
    exit;
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$order_query = "SELECT orders.*, customers.name AS customer_name, cars.brand, cars.model
                FROM orders
                INNER JOIN customers ON orders.customer_id = customers.id
                INNER JOIN cars ON orders.car_id = cars.id
                WHERE orders.id = $order_id";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "Order not found.";
    exit;
}

$order = mysqli_fetch_assoc($order_result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update order status
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $update_query = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['status_message'] = "Order status updated successfully!";
        header('Location: view_orders.php'); // Redirect to orders list
        exit;
    } else {
        echo "Error updating order status: " . mysqli_error($conn);
    }
}

?>

<div class="container mt-4">
    <h2>Update Order Status - <?= $order['customer_name'] ?> (<?= $order['brand'] . ' ' . $order['model'] ?>)</h2>
    <form action="update_order.php?order_id=<?= $order_id ?>" method="POST">
        <div class="form-group">
            <label for="status">Order Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Processing" <?= ($order['status'] == 'Processing') ? 'selected' : '' ?>>Processing</option>
                <option value="Shipped" <?= ($order['status'] == 'Shipped') ? 'selected' : '' ?>>Shipped</option>
                <option value="Delivered" <?= ($order['status'] == 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                <option value="Cancelled" <?= ($order['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
</div>

<?php include 'footer.php'; ?>
