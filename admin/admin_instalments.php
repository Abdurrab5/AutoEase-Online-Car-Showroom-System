<?php
include 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect if not logged in as admin
    exit;
}

if (!isset($_GET['order_id'])) {
    echo "Invalid order ID.";
    exit;
}

$order_id = intval($_GET['order_id']);

// Fetch instalments for the order
$instalment_query = "SELECT * FROM instalments WHERE order_id = $order_id";
$instalment_result = mysqli_query($conn, $instalment_query);

?>
 

<div class="container mt-4">
    <h2>Instalments for Order ID <?= $order_id ?></h2>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Instalment Number</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($instalment = mysqli_fetch_assoc($instalment_result)): ?>
                <tr>
                    <td><?= $instalment['id'] ?></td>
                    <td><?= $instalment['instalment_number'] ?></td>
                    <td><?= $instalment['amount'] ?></td>
                    <td><?= $instalment['due_date'] ?></td>
                    <td><?= $instalment['status'] ?></td>
                    <td>
                        <a href="update_instalment_status.php?instalment_id=<?= $instalment['id'] ?>" class="btn btn-primary">Update Status</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; // Include admin footer ?>
