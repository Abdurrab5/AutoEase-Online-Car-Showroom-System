<?php
include 'header.php';
include 'includes/functions.php'; // where createInstallmentSchedule() is defined

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit;
}

if (!isset($_SESSION['customer_id'])) {
    echo "You must be logged in to place an order.";
    exit;
}

// Collect form data
$customer_id = $_SESSION['customer_id'];
$car_id = intval($_POST['car_id']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$plan_id = isset($_POST['installment_plan']) ? intval($_POST['installment_plan']) : NULL;

 

// Check if car exists and is available
 
$car_check = mysqli_query($conn, "SELECT * FROM cars WHERE id = $car_id AND available = 1 LIMIT 1");
if (mysqli_num_rows($car_check) === 0) {
    echo "Car not available.";
    exit;
}

$car = mysqli_fetch_assoc($car_check);
$car_price = $car['price'];

// Calculate delivery charge based on city
$delivery_charge = 0;

$delivery_query = $conn->prepare("SELECT charge FROM delivery_charges WHERE city = ?");
$delivery_query->bind_param("s", $city);
$delivery_query->execute();
$delivery_result = $delivery_query->get_result();

if ($delivery_result && $delivery_result->num_rows > 0) {
    $delivery_row = $delivery_result->fetch_assoc();
    $delivery_charge = $delivery_row['charge'];
} else {
    echo "Delivery not available for the selected city.";
    exit;
}


$total_amount = $car_price + $delivery_charge;

// Insert order
$down_payment_method = $_POST['down_payment_method']; // cash/card
$delivery_payment_method = $_POST['delivery_charge_method']; // cash/card

// Modify INSERT query
$order_query = "INSERT INTO orders (customer_id, car_id, payment_type, delivery_city, delivery_charge, total_amount, down_payment_method, delivery_charge_method, down_payment_amount, status, order_date, plan_id)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW(), ?)";

$stmt = $conn->prepare($order_query);
$stmt->bind_param("iissddssdii", $customer_id, $car_id, $payment_type, $city, $delivery_charge, $total_amount, $down_payment_method, $delivery_payment_method, $down_payment, $plan_id);


if ($stmt->execute()) {
    $order_id = $stmt->insert_id;
    $order_date = date('Y-m-d');

    // Auto-generate installment schedule if needed
   

    if ($payment_type === 'installment' && $plan_id) {
        $plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM installment_plans WHERE id = $plan_id"));
        $down_payment = $plan['down_payment'];
        
        $remaining_amount = $car_price - $down_payment;
        $result = createInstallmentSchedule($conn, $order_id, $remaining_amount, $plan_id, $order_date);
        if (!$result) {
            echo "Failed to create installment schedule.<br>";
        }
    }

    $_SESSION['order_id'] = $order_id;
    $_SESSION['order_message'] = "Your order has been placed successfully. Order ID: " . $order_id;

    header("Location: order_confirmation.php");
    exit;
} else {
    echo "Error placing order.";
}
?>
 