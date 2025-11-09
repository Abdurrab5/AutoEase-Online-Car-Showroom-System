<?php
 
include 'header.php'; // your DB connection file

if (!isset($_SESSION['customer_id'])) {
    echo "Unauthorized access.";
    exit;
}

$customer_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = intval($_POST['car_id']);
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);
    $payment_type = $_POST['payment_type'];
    $plan_id = isset($_POST['installment_plan']) ? intval($_POST['installment_plan']) : null;

    // Fetch car details
    $car_result = mysqli_query($conn, "SELECT * FROM cars WHERE id = $car_id AND available = 1");
    if (!$car_result || mysqli_num_rows($car_result) === 0) {
        echo "Car not found.";
        exit;
    }
    $car = mysqli_fetch_assoc($car_result);
    $car_price = $car['price'];

    // Fetch delivery charge
    $delivery_result = mysqli_query($conn, "SELECT charge FROM delivery_charges WHERE city = '" . mysqli_real_escape_string($conn, $city) . "' LIMIT 1");
    if (!$delivery_result || mysqli_num_rows($delivery_result) === 0) {
        echo "Delivery city not found.";
        exit;
    }
    $delivery_data = mysqli_fetch_assoc($delivery_result);
    $delivery_charge = floatval($delivery_data['charge']);

    // Set defaults
    $status = 1; // Order status
    $order_date = date("Y-m-d");
    $payment_status = 0; // unpaid
    $down_payment_method = 'manual';
    $delivery_charge_method = 'manual';

    // Default values
    $down_payment_amount = null;
    $total_amount = $car_price + $delivery_charge;

    // If installment selected
    if ($payment_type === 'installment' && $plan_id) {
        $plan_result = mysqli_query($conn, "SELECT * FROM installment_plans WHERE id = $plan_id LIMIT 1");
        if (!$plan_result || mysqli_num_rows($plan_result) === 0) {
            echo "Installment plan not found.";
            exit;
        }

        $plan = mysqli_fetch_assoc($plan_result);
        $down_percent = $plan['down_payment_percent'];
        $down_payment_amount = round($car_price * ($down_percent / 100));
        $total_amount = $down_payment_amount + $delivery_charge;
    } else {
        $plan_id = null;
    }

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, car_id, payment_type, delivery_city, delivery_address, delivery_charge, total_amount, status, order_date, payment_status, plan_id, down_payment_method, delivery_charge_method, down_payment_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iisssddsisissd",
        $customer_id,
        $car_id,
        $payment_type,
        $city,
        $address,
        $delivery_charge,
        $total_amount,
        $status,
        $order_date,
        $payment_status,
        $plan_id,
        $down_payment_method,
        $delivery_charge_method,
        $down_payment_amount
    );

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Insert installment details if applicable
        if ($payment_type === 'installment' && $plan_id) {
            $duration = $plan['duration_months'];
            $interest = $plan['interest_rate_percent'];

            $loan_amount = $car_price - $down_payment_amount;
            $interest_amount = $loan_amount * ($interest / 100);
            $total_payable = $loan_amount + $interest_amount;
            $monthly_installment = round($total_payable / $duration);

            $installment_stmt = $conn->prepare("INSERT INTO installments (order_id, plan_id, duration_months, interest_rate_percent, down_payment_amount, monthly_installment, total_installment_amount, remaining_months) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $installment_stmt->bind_param(
                "iiiddddi",
                $order_id,
                $plan_id,
                $duration,
                $interest,
                $down_payment_amount,
                $monthly_installment,
                $total_payable,
                $duration
            );

            if (!$installment_stmt->execute()) {
                echo "Order placed, but failed to add installment: " . $installment_stmt->error;
                exit;
            }

            $installment_stmt->close();
        }

        echo "Order placed successfully!";
    } else {
        echo "Error placing order: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
