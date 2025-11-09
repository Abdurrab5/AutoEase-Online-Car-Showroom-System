<?php
include 'header.php';
 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : '';
    $customer_id = intval($_POST['customer_id']);
    $order_id = intval($_POST['order_id']);
    $amount_paid = floatval($_POST['amount_paid']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $payment_date = $_POST['payment_date'];
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    if ($id) {
        $sql = "UPDATE payments SET customer_id=$customer_id, order_id=$order_id, amount_paid=$amount_paid,
                payment_method='$payment_method', payment_status='$payment_status', payment_date='$payment_date', note='$note' 
                WHERE id=$id";
    } else {
        $sql = "INSERT INTO payments (customer_id, order_id, amount_paid, payment_method, payment_status, payment_date, note) 
                VALUES ($customer_id, $order_id, $amount_paid, '$payment_method', '$payment_status', '$payment_date', '$note')";
    }

    mysqli_query($conn, $sql);
    header("Location: manage_payments.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM payments WHERE id=$id");
    header("Location: manage_payments.php");
    exit;
}

// Fetch customers and orders for dropdowns
$customers = mysqli_query($conn, "SELECT id, name FROM customers ORDER BY name");
$orders = mysqli_query($conn, "SELECT id FROM orders ORDER BY id DESC");

// Get payments list
$payments = mysqli_query($conn, "SELECT p.*, c.name AS customer_name FROM payments p 
                                 JOIN customers c ON p.customer_id = c.id
                                 ORDER BY p.payment_date DESC");

// Edit mode
$edit_data = ['id'=>'', 'customer_id'=>'', 'order_id'=>'', 'amount_paid'=>'', 'payment_method'=>'', 'payment_status'=>'', 'payment_date'=>'', 'note'=>''];
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM payments WHERE id=$id");
    if ($row = mysqli_fetch_assoc($result)) {
        $edit_data = $row;
    }
}
?>

<div class="container mt-4">
    <h2>Manage Payments</h2>

    <form method="post" class="mb-4">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <div class="row">
            <div class="col-md-3">
                <label>Customer</label>
                <select name="customer_id" class="form-control" required>
                    <option value="">Select Customer</option>
                    <?php while ($cust = mysqli_fetch_assoc($customers)): ?>
                        <option value="<?= $cust['id'] ?>" <?= $cust['id'] == $edit_data['customer_id'] ? 'selected' : '' ?>>
                            <?= $cust['name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Order ID</label>
                <select name="order_id" class="form-control" required>
                    <option value="">Select Order</option>
                    <?php while ($ord = mysqli_fetch_assoc($orders)): ?>
                        <option value="<?= $ord['id'] ?>" <?= $ord['id'] == $edit_data['order_id'] ? 'selected' : '' ?>>
                            #<?= $ord['id'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" class="form-control" required value="<?= $edit_data['amount_paid'] ?>">
            </div>
            <div class="col-md-2">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="full" <?= $edit_data['payment_method'] == 'full' ? 'selected' : '' ?>>Full</option>
                    <option value="installment" <?= $edit_data['payment_method'] == 'installment' ? 'selected' : '' ?>>Installment</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Status</label>
                <select name="payment_status" class="form-control" required>
                    <option value="paid" <?= $edit_data['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="partial" <?= $edit_data['payment_status'] == 'partial' ? 'selected' : '' ?>>Partial</option>
                    <option value="pending" <?= $edit_data['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Payment Date</label>
                <input type="date" name="payment_date" class="form-control" required value="<?= $edit_data['payment_date'] ?>">
            </div>
            <div class="col-md-4 mt-2">
                <label>Note</label>
                <input type="text" name="note" class="form-control" value="<?= $edit_data['note'] ?>">
            </div>
            <div class="col-md-2 mt-4">
                <button type="submit" class="btn btn-primary mt-2"><?= $edit_data['id'] ? 'Update' : 'Add' ?></button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Order</th>
                <th>Amount (PKR)</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pay = mysqli_fetch_assoc($payments)): ?>
            <tr>
                <td><?= $pay['id'] ?></td>
                <td><?= $pay['customer_name'] ?></td>
                <td>#<?= $pay['order_id'] ?></td>
                <td><?= number_format($pay['amount_paid'], 2) ?></td>
                <td><?= ucfirst($pay['payment_method']) ?></td>
                <td><?= ucfirst($pay['payment_status']) ?></td>
                <td><?= $pay['payment_date'] ?></td>
                <td><?= $pay['note'] ?></td>
                <td>
                    <a href="manage_payments.php?edit=<?= $pay['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="manage_payments.php?delete=<?= $pay['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete payment?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
