<?php
include 'header.php';
 

// Fetch installments with related data
$query = "SELECT i.id, i.order_id, i.due_date, i.amount, i.status, c.name AS customer_name, ip.total_amount, ip.months
          FROM installments i
          JOIN orders o ON i.order_id = o.id
          JOIN customers c ON o.customer_id = c.id
          JOIN installment_plans ip ON o.id = ip.order_id
          ORDER BY i.due_date ASC";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2>Manage Installments</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Installment ID</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['order_id'] ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= $row['due_date'] ?></td>
                <td>PKR <?= number_format($row['amount']) ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <form action="update_installment_status.php" method="POST" class="d-flex">
                        <input type="hidden" name="installment_id" value="<?= $row['id'] ?>">
                        <select name="status" class="form-select me-2">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Paid" <?= $row['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="Overdue" <?= $row['status'] == 'Overdue' ? 'selected' : '' ?>>Overdue</option>
                        </select>
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
