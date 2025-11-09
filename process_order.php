<?php
 
include 'header.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php'); // Redirect if not logged in as customer
    exit;
}

$customer_id = $_SESSION['customer_id'];
$car_id = intval($_POST['car_id']);
$payment_type = $_POST['payment_type'];
$delivery_city = $_POST['delivery_city'];
$total_amount = floatval($_POST['total_amount']);
$delivery_charge = floatval($_POST['delivery_charge']);

// Check if the car exists
$car_query = "SELECT * FROM cars WHERE id = $car_id";
$car_result = mysqli_query($conn, $car_query);

if (mysqli_num_rows($car_result) == 0) {
    echo "Invalid car ID.";
    exit;
}

$car = mysqli_fetch_assoc($car_result);

// Insert order into the database
$order_query = "INSERT INTO orders (customer_id, car_id, payment_type, delivery_city, delivery_charge, total_amount, status, order_date)
                VALUES ('$customer_id', '$car_id', '$payment_type', '$delivery_city', '$delivery_charge', '$total_amount', 'Pending', NOW())";

if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn);

    if ($payment_type == 'Instalments') {
        // Create instalment schedule (e.g., 3 instalments)
        $instalment_amount = $total_amount / 3;

        for ($i = 1; $i <= 3; $i++) {
            $instalment_query = "INSERT INTO instalments (order_id, instalment_number, amount, due_date, status)
                                 VALUES ('$order_id', '$i', '$instalment_amount', DATE_ADD(NOW(), INTERVAL $i MONTH), 'Pending')";
            mysqli_query($conn, $instalment_query);
        }
    }

    $_SESSION['status_message'] = "Order placed successfully.";
    header('Location: customer_dashboard.php');
    exit;
} else {
    echo "Error processing your order.";
}
?>
