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
$order_query = "SELECT * FROM orders WHERE id = $order_id AND customer_id = $customer_id";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "Order not found or you're not authorized to cancel this order.";
    exit;
}

$order = mysqli_fetch_assoc($order_result);

// Check if the order is within the cancelable timeframe (24 hours)
$order_date = new DateTime($order['order_date']);
$current_date = new DateTime();
$interval = $order_date->diff($current_date);

if ($interval->days <= 1) {
    // Cancel the order
    $cancel_query = "UPDATE orders SET status = 'Cancelled' WHERE id = $order_id";
    if (mysqli_query($conn, $cancel_query)) {
        $_SESSION['status_message'] = "Order canceled successfully.";
        header('Location: customer_dashboard.php');
        exit;
    } else {
        echo "Error canceling order.";
    }
} else {
    echo "Order cannot be canceled as it exceeds the 24-hour limit.";
}
?>
