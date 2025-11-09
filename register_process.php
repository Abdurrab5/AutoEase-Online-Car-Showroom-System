<?php
include 'includes/db_connect.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $guarantor_name = $_POST['guarantor_name'];
    $guarantor_bank_details = $_POST['guarantor_bank_details'];

    $stmt = $conn->prepare("INSERT INTO customers (name, email, password, phone, city, guarantor_name, guarantor_bank_details) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $password, $phone, $city, $guarantor_name, $guarantor_bank_details);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: Email may already exist.'); window.history.back();</script>";
    }

    $stmt->close();
}
?>
