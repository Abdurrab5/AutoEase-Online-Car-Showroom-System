<?php
include 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect if not logged in as admin
    exit;
}

if (!isset($_GET['car_id'])) {
    echo "Invalid car ID.";
    exit;
}

$car_id = intval($_GET['car_id']);

// Delete car from database
$delete_query = "DELETE FROM cars WHERE id = $car_id";
if (mysqli_query($conn, $delete_query)) {
    $_SESSION['status_message'] = "Car deleted successfully!";
    header('Location: dashboard.php'); // Redirect to dashboard after deletion
    exit;
} else {
    echo "Error deleting car: " . mysqli_error($conn);
}
?>
