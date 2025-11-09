<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect if not logged in as admin
    exit;
}

if (!isset($_GET['instalment_id'])) {
    echo "Invalid instalment ID.";
    exit;
}

$instalment_id = intval($_GET['instalment_id']);

// Fetch instalment details
$instalment_query = "SELECT * FROM instalments WHERE id = $instalment_id";
$instalment_result = mysqli_query($conn, $instalment_query);
$instalment = mysqli_fetch_assoc($instalment_result);

if (!$instalment) {
    echo "Instalment not found.";
    exit;
}

// Update instalment status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];

    $update_query = "UPDATE instalments SET status = '$new_status' WHERE id = $instalment_id";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['status_message'] = "Instalment status updated successfully.";
        header('Location: admin_instalments.php?order_id=' . $instalment['order_id']);
        exit;
    } else {
        echo "Error updating instalment status.";
    }
}

?>

<?php include 'header.php'; // Include admin header ?>

<div class="container mt-4">
    <h2>Update Instalment Status</h2>
    <form method="POST">
        <div class="form-group">
            <label for="status">Select New Status</label>
            <select class="form-control" name="status" id="status" required>
                <option value="Pending" <?= $instalment['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Paid" <?= $instalment['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
</div>

<?php include 'admin_footer.php'; // Include admin footer ?>
