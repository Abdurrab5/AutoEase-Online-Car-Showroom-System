<?php
include '../includes/db_connect.php';
include 'header.php';

$id = intval($_GET['id']);
$query = "SELECT * FROM customers WHERE id = $id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $guarantor_name = $_POST['guarantor_name'];
    $guarantor_bank_details = $_POST['guarantor_bank_details'];

    $sql = "UPDATE customers SET
            name = '$name',
            email = '$email',
            phone = '$phone',
            city = '$city',
            guarantor_name = '$guarantor_name',
            guarantor_bank_details = '$guarantor_bank_details'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_users.php?msg=Customer updated");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container mt-4">
    <h2>Edit Customer</h2>
    <form method="post">
        <div class="mb-2"><label>Name:</label><input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required></div>
        <div class="mb-2"><label>Email:</label><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required></div>
        <div class="mb-2"><label>Phone:</label><input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control" required></div>
        <div class="mb-2"><label>City:</label><input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>" class="form-control" required></div>
        <div class="mb-2"><label>Guarantor Name:</label><input type="text" name="guarantor_name" value="<?= htmlspecialchars($user['guarantor_name']) ?>" class="form-control" required></div>
        <div class="mb-3"><label>Guarantor Bank Details:</label><textarea name="guarantor_bank_details" class="form-control" required><?= htmlspecialchars($user['guarantor_bank_details']) ?></textarea></div>
        <input type="submit" value="Update Customer" class="btn btn-success">
    </form>
</div>

<?php include 'footer.php'; ?>
