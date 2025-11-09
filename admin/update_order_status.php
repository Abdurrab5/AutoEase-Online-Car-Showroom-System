<?php
 
include 'header.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Update the order status in the database
    $update_query = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['status_message'] = "Order status updated successfully!";
    } else {
        $_SESSION['status_message'] = "Error updating order status.";
    }

    // Redirect back to the admin dashboard
    header('Location: manage_installments.php');
    exit;
}

?>
