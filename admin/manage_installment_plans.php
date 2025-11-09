<?php
 
include 'header.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duration = $_POST['duration_months'];
    $down_payment = $_POST['down_payment_percent'];
    $interest = $_POST['interest_rate_percent'];
    $description = $_POST['description'];

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        // Update
        $id = $_POST['edit_id'];
        $stmt = $conn->prepare("UPDATE installment_plans SET duration_months=?, down_payment_percent=?, interest_rate_percent=?, description=? WHERE id=?");
        $stmt->bind_param("iddsi", $duration, $down_payment, $interest, $description, $id);
        $stmt->execute();
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO installment_plans (duration_months, down_payment_percent, interest_rate_percent, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idds", $duration, $down_payment, $interest, $description);
        $stmt->execute();
    }
    header("Location: manage_installment_plans.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM installment_plans WHERE id = $delete_id");
    header("Location: manage_installment_plans.php");
    exit();
}

// Fetch all installment plans
$plans = $conn->query("SELECT * FROM installment_plans ORDER BY duration_months ASC");
?>



<div class="container mt-4">
   

    <form method="post" class="mb-4">
    <h2>Manage Installment Plans</h2>
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="row g-2">
            <div class="col-md-2">
                <input type="number" class="form-control" name="duration_months" id="duration_months" placeholder="Duration (months)" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" class="form-control" name="down_payment_percent" id="down_payment_percent" placeholder="Down Payment %" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" class="form-control" name="interest_rate_percent" id="interest_rate_percent" placeholder="Interest Rate %" required>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="description" id="description" placeholder="Description (optional)">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Save</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Duration</th>
                <th>Down Payment %</th>
                <th>Interest Rate %</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $plans->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['duration_months'] ?> months</td>
                    <td><?= $row['down_payment_percent'] ?>%</td>
                    <td><?= $row['interest_rate_percent'] ?>%</td>
                    <td><?= $row['description'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick='editPlan(<?= json_encode($row) ?>)'>Edit</button>
                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this plan?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function editPlan(plan) {
    document.getElementById('edit_id').value = plan.id;
    document.getElementById('duration_months').value = plan.duration_months;
    document.getElementById('down_payment_percent').value = plan.down_payment_percent;
    document.getElementById('interest_rate_percent').value = plan.interest_rate_percent;
    document.getElementById('description').value = plan.description;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php include 'footer.php'; ?>
