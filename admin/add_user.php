<?php
include '../includes/db_connect.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $guarantor_name = $_POST['guarantor_name'];
    $guarantor_bank_details = $_POST['guarantor_bank_details'];

    $sql = "INSERT INTO customers (name, email, password, phone, city, guarantor_name, guarantor_bank_details)
            VALUES ('$name', '$email', '$password', '$phone', '$city', '$guarantor_name', '$guarantor_bank_details')";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_users.php?msg=Customer added");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container mt-4">
    <h2>Add New Customer</h2>
    <form method="post">
        <div class="mb-2"><label>Name:</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-2"><label>Email:</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-2"><label>Password:</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-2"><label>Phone:</label><input type="text" name="phone" class="form-control" required></div>
        <div class="mb-2"><label>City:</label><input type="text" name="city" class="form-control" required></div>
        <div class="mb-2"><label>Guarantor Name:</label><input type="text" name="guarantor_name" class="form-control" required></div>
        <div class="mb-3"><label>Guarantor Bank Details:</label><textarea name="guarantor_bank_details" class="form-control" required></textarea></div>
        <input type="submit" value="Add Customer" class="btn btn-primary">
    </form>
</div>

<?php include 'footer.php'; ?>
