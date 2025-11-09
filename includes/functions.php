<?php

function getTotalUsers($conn) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM customers");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function getTotalCars($conn) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM cars");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function getTotalOrders($conn) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function getPendingOrders($conn) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status = 'Pending'");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

 
function createInstallmentSchedule($conn, $order_id, $total_amount, $plan_id, $order_date) {
    // 1. Fetch plan details
    $plan_query = "SELECT duration_months AS months, 0 AS start_after_days FROM installment_plans WHERE id = ?";

    $stmt = $conn->prepare($plan_query);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return false; // plan not found
    }

    $plan = $result->fetch_assoc();
    $months = $plan['months'];
    $start_after_days = $plan['start_after_days'] ?? 0;

    // 2. Calculate monthly amount
    $monthly_amount = round($total_amount / $months, 2);

    // 3. Insert installments
    for ($i = 1; $i <= $months; $i++) {
        $due_date = date('Y-m-d', strtotime("+$start_after_days days +$i month", strtotime($order_date)));

        $insert_query = "INSERT INTO installments (order_id, due_date, amount, status) VALUES (?, ?, ?, 'Pending')";

        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("isd", $order_id, $due_date, $monthly_amount);
        $insert_stmt->execute();
    }

    return true;
}
